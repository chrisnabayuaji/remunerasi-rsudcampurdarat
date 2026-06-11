<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\PernikahanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pernikahan extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::pernikahan.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_pernikahan')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_pernikahan')->where('pernikahan_id', $id)->first();
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
      if ($id) {
        DB::table('mst_pernikahan')->where('pernikahan_id', $id)->update($d);
        $msg = 'Data pernikahan berhasil diperbarui';
      } else {
        DB::table('mst_pernikahan')->insert($d);
        $msg = 'Data pernikahan berhasil ditambahkan';
      }
      DbModel::commitTransaction();
      return fsResponse(true, $msg, $this->nav_url . '?n=' . $this->nav_id);
    } catch (\Exception $e) {
      DbModel::rollbackTransaction();
      return fsResponse(false, $e->getMessage());
    }
  }

  function delete($id, $token)
  {
    if (!fsValidateToken($token)) return fsResponse(false, 'Invalid token');

    DbModel::beginTransaction();
    try {
      DB::table('mst_pernikahan')->where('pernikahan_id', $id)->delete();
      DbModel::commitTransaction();
      return fsResponse(true, 'Data pernikahan berhasil dihapus', $this->nav_url . '?n=' . $this->nav_id);
    } catch (\Exception $e) {
      DbModel::rollbackTransaction();
      return fsResponse(false, $e->getMessage());
    }
  }

  function ajax_datatables()
  {
    return PernikahanModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300);

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      $simrsData = DB::connection('simrs')->table('mst_pernikahan')->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data pernikahan di SIMRS'
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
          $simrsIds[] = $record->pernikahan_id;

          $exists = DB::table('mst_pernikahan')->where('pernikahan_id', $record->pernikahan_id)->first();

          $data = [
            'pernikahan_id' => $record->pernikahan_id,
            'pernikahan_nm' => $record->pernikahan_nm,
            'satusehat_cd' => $record->satusehat_cd,
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
            DB::table('mst_pernikahan')->where('pernikahan_id', $record->pernikahan_id)->update($data);
            $stats['updated']++;
          } else {
            DB::table('mst_pernikahan')->insert($data);
            $stats['inserted']++;
          }
        } catch (\Exception $e) {
          $stats['errors']++;
          $stats['error_details'][] = "pernikahan_id: {$record->pernikahan_id} - " . $e->getMessage();
          Log::error("Error syncing pernikahan {$record->pernikahan_id}: " . $e->getMessage());
        }
      }

      $placeholders = implode(',', array_fill(0, count($simrsIds), '?'));
      $orphanedData = DB::table('mst_pernikahan')
        ->whereRaw("pernikahan_id NOT IN ($placeholders)", $simrsIds)
        ->where('deleted_st', 0)
        ->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_pernikahan')->where('pernikahan_id', $orphan->pernikahan_id)->update([
            'deleted_st' => 1,
            'active_st' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => 'sync_system',
          ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned pernikahan {$orphan->pernikahan_id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      DB::table('sync_log')->insert([
        'table_name' => 'mst_pernikahan',
        'sync_type' => 'full',
        'records_synced' => $stats['inserted'] + $stats['updated'],
        'status' => $stats['errors'] > 0 ? 'partial_success' : 'success',
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
      Log::error('Error syncing pernikahan: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
      ], 500);
    }
  }

  function get_last_sync()
  {
    $last = DB::table('sync_log')
      ->where('table_name', 'mst_pernikahan')
      ->orderBy('synced_at', 'desc')
      ->first();
    return response()->json($last);
  }
}
