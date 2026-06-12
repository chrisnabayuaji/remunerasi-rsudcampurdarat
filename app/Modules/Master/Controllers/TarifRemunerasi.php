<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\TarifRemunerasiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarifRemunerasi extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::tarifremunerasi.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_tarif_remunerasi')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_tarif_remunerasi')->where('id', $id)->first();
    if ($d['main']) {
      $d['main'] = json_decode(json_encode($d['main']), true);
    }
    // Also pass tariffs for selection
    $d['tarifs'] = DB::table('mst_tarif')->where('deleted_st', 0)->orderBy('tarif_nm')->get();
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
        $insertData = DbModel::insertData('mst_tarif_remunerasi', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('mst_tarif_remunerasi', $d, ['id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/tarifremunerasi?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/tarifremunerasi?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete(String $id, String $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('mst_tarif_remunerasi', ['id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/tarifremunerasi?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/tarifremunerasi?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return TarifRemunerasiModel::loadDatatables();
  }

  function sync()
  {
    set_time_limit(300); // 5 minutes timeout

    DB::beginTransaction();
    try {
      $startTime = microtime(true);

      // Get all data from SIMRS (including deleted)
      $simrsData = DB::connection('simrs')->table('mst_tarif_remunerasi')->get();

      if ($simrsData->isEmpty()) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => 'Tidak ada data tarif remunerasi di SIMRS'
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
        $simrsIds[] = $record->id;
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
          'id' => $record->id,
          'tarif_id' => $record->tarif_id,
          'kelompokkelas_id' => $record->kelompokkelas_id,
          'alokasi_insentif_id' => $record->alokasi_insentif_id,
          'kelompok_id' => $record->kelompok_id,
          'pelaku_st' => $record->pelaku_st,
          'nilai' => $record->nilai,
          'jasa_sarana' => $record->jasa_sarana,
          'jasa_layanan' => $record->jasa_layanan,
          'cost_center' => $record->cost_center,
          'revenue_center' => $record->revenue_center,
          'direksi' => $record->direksi,
          'direktur' => $record->direktur,
          'kabag_kasie' => $record->kabag_kasie,
          'post_rm' => $record->post_rm,
          'dokter_utama_dokter' => $record->dokter_utama_dokter,
          'dokter_utama_perawat' => $record->dokter_utama_perawat,
          'perawat_utama_dokter' => $record->perawat_utama_dokter,
          'perawat_utama_perawat' => $record->perawat_utama_perawat,
          'dengan_anestesi_dokter_operator' => $record->dengan_anestesi_dokter_operator,
          'dengan_anestesi_dokter_anestesi' => $record->dengan_anestesi_dokter_anestesi,
          'dengan_anestesi_perawat_ok' => $record->dengan_anestesi_perawat_ok,
          'tanpa_anestesi_dokter_operator' => $record->tanpa_anestesi_dokter_operator,
          'tanpa_anestesi_perawat_ok' => $record->tanpa_anestesi_perawat_ok,
          'supir' => $record->supir,
          'rekam_medis' => $record->rekam_medis,
          'cssd_laundry' => $record->cssd_laundry,
        ];
      }

      $updateColumns = [
        'tarif_id', 'kelompokkelas_id', 'alokasi_insentif_id', 'kelompok_id', 'pelaku_st',
        'nilai', 'jasa_sarana', 'jasa_layanan', 'cost_center', 'revenue_center', 'direksi',
        'direktur', 'kabag_kasie', 'post_rm', 'dokter_utama_dokter', 'dokter_utama_perawat',
        'perawat_utama_dokter', 'perawat_utama_perawat', 'dengan_anestesi_dokter_operator',
        'dengan_anestesi_dokter_anestesi', 'dengan_anestesi_perawat_ok',
        'tanpa_anestesi_dokter_operator', 'tanpa_anestesi_perawat_ok', 'supir',
        'rekam_medis', 'cssd_laundry', 'active_st', 'deleted_st', 'external_id',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'
      ];

      foreach (array_chunk($upsertData, 500) as $chunk) {
        DB::table('mst_tarif_remunerasi')->upsert($chunk, ['id'], $updateColumns);
        $stats['inserted_or_updated'] += count($chunk);
      }

      // Handle orphaned data (exists in local but not in SIMRS)
      $orphanedDataQuery = DB::table('mst_tarif_remunerasi')
        ->where('deleted_st', 0);

      if (count($simrsIds) < 1000) {
        $orphanedDataQuery->whereNotIn('id', $simrsIds);
      } else {
        $orphanedDataQuery->where(function($query) use ($simrsIds) {
          $chunks = array_chunk($simrsIds, 1000);
          foreach ($chunks as $chunk) {
            $query->whereNotIn('id', $chunk);
          }
        });
      }

      $orphanedData = $orphanedDataQuery->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_tarif_remunerasi')->where('id', $orphan->id)->update([
            'deleted_st' => 1,
            'active_st' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => 'sync_system',
          ]);
          $stats['deleted']++;
        } catch (\Exception $e) {
          $stats['errors']++;
          Log::error("Error deleting orphaned tarif_remunerasi {$orphan->id}: " . $e->getMessage());
        }
      }

      $duration = round(microtime(true) - $startTime, 2);

      // Log sync result
      $syncStatus = $stats['errors'] > 0 ? 'partial_success' : 'success';
      DB::table('sync_log')->insert([
        'table_name' => 'mst_tarif_remunerasi',
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
      Log::error('Error syncing tarif remunerasi: ' . $e->getMessage());

      // Log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_tarif_remunerasi',
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
      ->where('table_name', 'mst_tarif_remunerasi')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }
}
