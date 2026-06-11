<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\PenjaminModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Penjamin extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::penjamin.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_penjamin')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_penjamin')->where('penjamin_id', $id)->first();
    if ($d['main']) {
      $d['main'] = json_decode(json_encode($d['main']), true);
    }
    $d['form_act'] = $this->nav_url . '/save' . ($id ? '/' . $id : '') . '?n=' . $this->nav_id;
    return $this->renderView($this->template . 'formModal', $d);
  }

  function edit($id)
  {
    return $this->form_modal($id);
  }

  function save($id = null)
  {
    $d = fsPost();

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        $insertData = DbModel::insertData('mst_penjamin', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('mst_penjamin', $d, ['penjamin_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/penjamin?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/penjamin?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete($id, $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('mst_penjamin', ['penjamin_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/penjamin?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/penjamin?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return PenjaminModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300); // 5 minutes timeout

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      // Get all data from SIMRS (including deleted)
      $simrsData = DB::connection('simrs')->table('mst_penjamin')->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data penjamin di SIMRS'
        ]);
      }

      $stats = [
        'inserted' => 0,
        'updated' => 0,
        'deleted' => 0,
        'errors' => 0,
        'error_details' => []
      ];

      $simrsIds = [];

      // Process each record
      foreach ($simrsData as $record) {
        try {
          $simrsIds[] = $record->penjamin_id;

          $exists = DB::table('mst_penjamin')->where('penjamin_id', $record->penjamin_id)->first();

          $data = [
            'penjamin_id' => $record->penjamin_id,
            'penjamin_nm' => $record->penjamin_nm,
            'bpjs_st' => $record->bpjs_st ?? 1,
            'permanent_st' => $record->permanent_st ?? 1,
            'urut_no' => $record->urut_no ?? 99,
            'margin_farmasi' => $record->margin_farmasi,
            'ris_id' => $record->ris_id,
            'active_st' => $record->active_st ?? 1,
            'deleted_st' => $record->deleted_st ?? 0,
            'external_id' => $record->external_id,
            'created_at' => $record->created_at,
            'created_by' => $record->created_by,
            'updated_at' => $record->updated_at,
            'updated_by' => $record->updated_by,
            'deleted_at' => $record->deleted_at,
            'deleted_by' => $record->deleted_by,
          ];

          if ($exists) {
            DB::table('mst_penjamin')->where('penjamin_id', $record->penjamin_id)->update($data);
            $stats['updated']++;
          } else {
            DB::table('mst_penjamin')->insert($data);
            $stats['inserted']++;
          }
        } catch (\Exception $e) {
          $stats['errors']++;
          $stats['error_details'][] = "penjamin_id: {$record->penjamin_id} - " . $e->getMessage();
          Log::error("Error syncing penjamin {$record->penjamin_id}: " . $e->getMessage());
        }
      }

      // Handle orphaned data (exists in local but not in SIMRS)
      $orphanedData = DB::table('mst_penjamin')
        ->whereNotIn('penjamin_id', $simrsIds)
        ->where('deleted_st', 0)
        ->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_penjamin')->where('penjamin_id', $orphan->penjamin_id)->update([
            'deleted_st' => 1,
            'active_st' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => 'sync_system',
          ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned penjamin {$orphan->penjamin_id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      // Log sync result
      $syncStatus = $stats['errors'] > 0 ? 'partial_success' : 'success';
      DB::table('sync_log')->insert([
        'table_name' => 'mst_penjamin',
        'sync_type' => 'full',
        'records_synced' => $stats['inserted'] + $stats['updated'],
        'status' => $syncStatus,
        'error_message' => $stats['errors'] > 0 ? json_encode($stats['error_details']) : null,
        'synced_at' => date('Y-m-d H:i:s'),
        'synced_by' => session('user_id'),
      ]);

      DB::commit();

      $message = "Sinkronisasi selesai dalam {$duration} detik. ";
      $message .= "Ditambahkan: {$stats['inserted']}, ";
      $message .= "Diperbarui: {$stats['updated']}, ";
      $message .= "Dihapus: {$stats['deleted']}";

      if ($stats['errors'] > 0) {
        $message .= ", Error: {$stats['errors']}";
      }

      return response()->json([
        'success' => true,
        'message' => $message,
        'stats' => $stats,
        'duration' => $duration
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error syncing penjamin: ' . $e->getMessage());

      // Log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_penjamin',
        'sync_type' => 'full',
        'records_synced' => 0,
        'status' => 'error',
        'error_message' => $e->getMessage(),
        'synced_at' => date('Y-m-d H:i:s'),
        'synced_by' => session('user_id'),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
      ], 500);
    }
  }

  function get_last_sync()
  {
    $lastSync = DB::table('sync_log')
      ->where('table_name', 'mst_penjamin')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }
}
