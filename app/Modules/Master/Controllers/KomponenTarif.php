<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\KomponenTarifModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KomponenTarif extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::komponentarif.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_komponen_tarif')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_komponen_tarif')->where('komponentarif_id', $id)->first();
    if ($d['main']) {
      $d['main'] = json_decode(json_encode($d['main']), true);
    }
    
    // Get list of other components for parent selection
    $d['parent_list'] = DB::table('mst_komponen_tarif')
      ->where('deleted_st', 0)
      ->where('active_st', 1)
      ->when($id, function($query) use ($id) {
        return $query->where('komponentarif_id', '!=', $id);
      })
      ->orderBy('komponentarif_nm')
      ->get();

    $d['form_act'] = $this->nav_url . '/save' . ($id ? '/' . $id : '') . '?n=' . $this->nav_id;
    return $this->renderView($this->template . 'formModal', $d);
  }

  function edit(String $id)
  {
    return $this->form_modal($id);
  }

  function save($id = null)
  {
    $d = fsPost();

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        $insertData = DbModel::insertData('mst_komponen_tarif', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('mst_komponen_tarif', $d, ['komponentarif_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/komponentarif?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/komponentarif?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete(String $id, String $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('mst_komponen_tarif', ['komponentarif_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/komponentarif?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/komponentarif?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  // Set to public so routes scanner can automatically detect it
  function ajax_datatables()
  {
    return KomponenTarifModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300); // 5 minutes timeout

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      // Get all data from SIMRS (including deleted)
      $simrsData = DB::connection('simrs')->table('mst_komponen_tarif')->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data komponen tarif di SIMRS'
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

      foreach ($simrsData as $record) {
        try {
          $simrsIds[] = $record->komponentarif_id;

          // Check if exists locally
          $exists = DB::table('mst_komponen_tarif')
            ->where('komponentarif_id', $record->komponentarif_id)
            ->first();

          $data = [
            'komponentarif_id' => $record->komponentarif_id,
            'komponentarif_nm' => $record->komponentarif_nm,
            'urut_no' => $record->urut_no,
            'grupkomponentarif_id' => $record->grupkomponentarif_id,
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
            // Preserve the parent value assigned locally
            $data['komponentarif_parent'] = $exists->komponentarif_parent;
            
            DB::table('mst_komponen_tarif')
              ->where('komponentarif_id', $record->komponentarif_id)
              ->update($data);
            $stats['updated']++;
          } else {
            // New record, parent is null
            $data['komponentarif_parent'] = null;
            
            DB::table('mst_komponen_tarif')->insert($data);
            $stats['inserted']++;
          }
        } catch (\Exception $e) {
          $stats['errors']++;
          $stats['error_details'][] = "ID {$record->komponentarif_id}: " . $e->getMessage();
          Log::error("Error syncing komponen tarif {$record->komponentarif_id}: " . $e->getMessage());
        }
      }

      // Handle orphaned data (exists in local but not in SIMRS)
      $placeholders = implode(',', array_fill(0, count($simrsIds), '?'));
      $orphanedData = DB::table('mst_komponen_tarif')
        ->whereRaw("komponentarif_id NOT IN ($placeholders)", $simrsIds)
        ->where('deleted_st', 0)
        ->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_komponen_tarif')
            ->where('komponentarif_id', $orphan->komponentarif_id)
            ->update([
              'deleted_st' => 1,
              'active_st' => 0,
              'deleted_at' => date('Y-m-d H:i:s'),
              'deleted_by' => 'sync_system',
            ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned komponen tarif {$orphan->komponentarif_id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      // Log sync result
      $syncStatus = $stats['errors'] > 0 ? 'partial_success' : 'success';
      DB::table('sync_log')->insert([
        'table_name' => 'mst_komponen_tarif',
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

      return response()->json([
        'success' => true,
        'message' => $message,
        'stats' => $stats,
        'duration' => $duration
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error syncing komponen tarif: ' . $e->getMessage());

      // Log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_komponen_tarif',
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
      ->where('table_name', 'mst_komponen_tarif')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }
}
