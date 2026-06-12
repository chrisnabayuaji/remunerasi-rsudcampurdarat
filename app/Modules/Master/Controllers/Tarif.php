<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\TarifModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Tarif extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::tarif.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_tarif')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    $d['list_paket'] = DB::table('mst_tarif')
      ->where('tarif_tp', 'G')
      ->where('deleted_st', 0)
      ->orderBy('tarif_id', 'asc')
      ->get();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_tarif')->where('tarif_id', $id)->first();
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
        $insertData = DbModel::insertData('mst_tarif', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('mst_tarif', $d, ['tarif_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/tarif?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/tarif?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete(String $id, String $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('mst_tarif', ['tarif_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/tarif?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/tarif?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return TarifModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300); // 5 minutes timeout

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      // Get all data from SIMRS (including deleted) with max nominal from tk (tarif_kelas detail)
      $simrsData = DB::connection('simrs')
        ->table('mst_tarif as t')
        ->select('t.*')
        ->selectRaw('(SELECT MAX(tk.nominal) FROM mst_tarif_kelas tk WHERE tk.tarif_id = t.tarif_id AND tk.deleted_st = 0) as nominal')
        ->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data tarif di SIMRS'
        ]);
      }

      $stats = [
        'inserted_or_updated' => 0,
        'deleted' => 0,
        'errors' => 0,
        'error_details' => []
      ];

      $simrsIds = [];
      $upsertData = [];

      foreach ($simrsData as $record) {
        $simrsIds[] = $record->tarif_id;
        $upsertData[] = [
          'created_at' => $record->created_at,
          'created_by' => $record->created_by,
          'updated_at' => $record->updated_at,
          'updated_by' => $record->updated_by,
          'deleted_at' => $record->deleted_at,
          'deleted_by' => $record->deleted_by,
          'deleted_st' => $record->deleted_st ?? 0,
          'active_st' => $record->active_st ?? 1,
          'external_id' => $record->external_id,
          'tarif_id' => $record->tarif_id,
          'tarif_parent' => $record->tarif_parent,
          'kelompoktarif_id' => $record->kelompoktarif_id,
          'akun_id' => $record->akun_id,
          'inacbg_id' => $record->inacbg_id,
          'tarif_nm' => $record->tarif_nm,
          'tarif_tp' => $record->tarif_tp,
          'tipetarif_id' => $record->tipetarif_id,
          'tarif_map' => $record->tarif_map,
          'order_list' => $record->order_list,
          'lis_map' => $record->lis_map,
          'external_id_2' => $record->external_id_2,
          'icd9_id' => $record->icd9_id,
          'loinc_id' => $record->loinc_id,
          'loinc_desc' => $record->loinc_desc,
          'tarifsatuan_id' => $record->tarifsatuan_id ?? '01',
          'score' => $record->score,
          'bhp_st' => $record->bhp_st ?? 0,
          'tipesampel_id' => $record->tipesampel_id,
          'tipesampel_cd' => $record->tipesampel_cd,
          'sktarif_id' => $record->sktarif_id,
          'modality_id' => $record->modality_id,
          'unit_cost' => $record->unit_cost,
          'kelompokkelas_id' => $record->kelompokkelas_id,
          'parent_cd' => $record->parent_cd,
          'tarif_cd' => $record->tarif_cd,
          'loinc_reference' => $record->loinc_reference,
          'nominal' => $record->nominal ?? 0,
        ];
      }

      // Bulk upsert using chunks of 500
      $updateColumns = [
        'tarif_nm',
        'tarif_parent',
        'kelompoktarif_id',
        'akun_id',
        'inacbg_id',
        'tarif_tp',
        'tipetarif_id',
        'tarif_map',
        'order_list',
        'lis_map',
        'external_id_2',
        'icd9_id',
        'loinc_id',
        'loinc_desc',
        'tarifsatuan_id',
        'score',
        'bhp_st',
        'tipesampel_id',
        'tipesampel_cd',
        'sktarif_id',
        'modality_id',
        'unit_cost',
        'kelompokkelas_id',
        'parent_cd',
        'tarif_cd',
        'loinc_reference',
        'active_st',
        'deleted_st',
        'external_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'nominal'
      ];

      foreach (array_chunk($upsertData, 500) as $chunk) {
        DB::table('mst_tarif')->upsert($chunk, ['tarif_id'], $updateColumns);
        $stats['inserted_or_updated'] += count($chunk);
      }

      // Handle orphaned data (exists in local but not in SIMRS)
      $orphanedDataQuery = DB::table('mst_tarif')
        ->where('deleted_st', 0);

      // Avoid passing too many parameters if the set is very large
      if (count($simrsIds) < 1000) {
        $orphanedDataQuery->whereNotIn('tarif_id', $simrsIds);
      } else {
        // Chunk or use temporary table if too large, but for 4000 rows, a raw subquery or batching whereNotIn is fine
        // Let's do it in batches of 1000 for whereNotIn
        $orphanedDataQuery->where(function ($query) use ($simrsIds) {
          $chunks = array_chunk($simrsIds, 1000);
          foreach ($chunks as $chunk) {
            $query->whereNotIn('tarif_id', $chunk);
          }
        });
      }

      $orphanedData = $orphanedDataQuery->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_tarif')->where('tarif_id', $orphan->tarif_id)->update([
            'deleted_st' => 1,
            'active_st' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => 'sync_system',
          ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned tarif {$orphan->tarif_id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      // Log sync result
      $syncStatus = $stats['errors'] > 0 ? 'partial_success' : 'success';
      DB::table('sync_log')->insert([
        'table_name' => 'mst_tarif',
        'sync_type' => 'full',
        'records_synced' => $stats['inserted_or_updated'],
        'status' => $syncStatus,
        'error_message' => $stats['errors'] > 0 ? json_encode($stats['error_details']) : null,
        'synced_at' => date('Y-m-d H:i:s'),
        'synced_by' => session('user_id'),
      ]);

      DB::commit();

      $message = "Sinkronisasi selesai dalam {$duration} detik. ";
      $message .= "Ditambahkan/Diperbarui: {$stats['inserted_or_updated']}, ";
      $message .= "Dihapus (soft delete): {$stats['deleted']}";

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
      Log::error('Error syncing tarif: ' . $e->getMessage());

      // Log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_tarif',
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
      ->where('table_name', 'mst_tarif')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }

  function set_filter()
  {
    $tarif_tp = request()->get('tarif_tp');
    session(['tarif_parent_filter' => $tarif_tp]);
    return response()->json(['success' => true]);
  }
}
