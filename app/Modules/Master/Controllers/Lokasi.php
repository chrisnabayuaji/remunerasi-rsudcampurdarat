<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\LokasiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Lokasi extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::lokasi.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_lokasi')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_lokasi')->where('lokasi_id', $id)->first();
    if ($d['main']) {
      $d['main'] = json_decode(json_encode($d['main']), true);
    }
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
        $insertData = DbModel::insertData('mst_lokasi', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('mst_lokasi', $d, ['lokasi_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/lokasi?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/lokasi?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete(String $id, String $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('mst_lokasi', ['lokasi_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/lokasi?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/lokasi?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return LokasiModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300); // 5 minutes timeout

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      // Get all data from SIMRS (including deleted)
      $simrsData = DB::connection('simrs')->table('mst_lokasi')->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data lokasi di SIMRS'
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
          $simrsIds[] = $record->lokasi_id;

          $exists = DB::table('mst_lokasi')->where('lokasi_id', $record->lokasi_id)->first();

          $data = [
            'lokasi_id' => $record->lokasi_id,
            'lokasi_parent' => $record->lokasi_parent,
            'lokasi_nm' => $record->lokasi_nm,
            'lokasi_tp' => $record->lokasi_tp,
            'jenisregistrasi_id' => $record->jenisregistrasi_id,
            'lokasiloket_id' => $record->lokasiloket_id,
            'kelasdefault_id' => $record->kelasdefault_id,
            'memilikibed_st' => $record->memilikibed_st ?? 0,
            'lokasidepo_st' => $record->lokasidepo_st ?? 0,
            'bpjs_cd' => $record->bpjs_cd,
            'antrian_cd' => $record->antrian_cd,
            'lokasi_map' => $record->lokasi_map,
            'bpjs_nm' => $record->bpjs_nm,
            'shift_id' => $record->shift_id,
            'lokasidepo_id' => $record->lokasidepo_id,
            'lokasiapotek_id' => $record->lokasiapotek_id,
            'lokasiapotek_st' => $record->lokasiapotek_st ?? 0,
            'registrasionline_st' => $record->registrasionline_st ?? 0,
            'lokasi_submap' => $record->lokasi_submap,
            'monitoring_st' => $record->monitoring_st ?? 0,
            'ihs_id' => $record->ihs_id,
            'jenislokasi_id' => $record->jenislokasi_id,
            'bpjs_sub_cd' => $record->bpjs_sub_cd,
            'lokasikasir_id' => $record->lokasikasir_id,
            'lokasikasir_st' => $record->lokasikasir_st ?? 0,
            'jenispelayanansirs_id' => $record->jenispelayanansirs_id,
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
            DB::table('mst_lokasi')->where('lokasi_id', $record->lokasi_id)->update($data);
            $stats['updated']++;
          } else {
            DB::table('mst_lokasi')->insert($data);
            $stats['inserted']++;
          }
        } catch (\Exception $e) {
          $stats['errors']++;
          $stats['error_details'][] = "lokasi_id: {$record->lokasi_id} - " . $e->getMessage();
          Log::error("Error syncing lokasi {$record->lokasi_id}: " . $e->getMessage());
        }
      }

      // Handle orphaned data (exists in local but not in SIMRS)
      $placeholders = implode(',', array_fill(0, count($simrsIds), '?'));
      $orphanedData = DB::table('mst_lokasi')
        ->whereRaw("lokasi_id NOT IN ($placeholders)", $simrsIds)
        ->where('deleted_st', 0)
        ->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_lokasi')->where('lokasi_id', $orphan->lokasi_id)->update([
            'deleted_st' => 1,
            'active_st' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => 'sync_system',
          ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned lokasi {$orphan->lokasi_id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      // Log sync result
      $syncStatus = $stats['errors'] > 0 ? 'partial_success' : 'success';
      DB::table('sync_log')->insert([
        'table_name' => 'mst_lokasi',
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
      Log::error('Error syncing lokasi: ' . $e->getMessage());

      // Log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_lokasi',
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
      ->where('table_name', 'mst_lokasi')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }
}
