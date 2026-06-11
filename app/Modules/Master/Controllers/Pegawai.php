<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\PegawaiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pegawai extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::pegawai.';
  }

  function index()
  {
    $d = [];
    $d['last_sync'] = DB::table('sync_log')
      ->where('table_name', 'mst_pegawai')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DB::table('mst_pegawai')->where('pegawai_id', $id)->first();
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

    DB::beginTransaction();
    try {
      if ($id == null) {
        $d['pegawai_id'] = generateId('mst_pegawai', 'pegawai_id', 12);
        $d['created_at'] = now();
        $d['created_by'] = session('user_id');
        $insertData = DB::table('mst_pegawai')->insert($d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
      } else {
        $d['updated_at'] = now();
        $d['updated_by'] = session('user_id');
        $updateData = DB::table('mst_pegawai')->where('pegawai_id', $id)->update($d);
        if ($updateData === false) {
          throw new \Exception("Gagal mengubah data", 1);
        }
      }

      DB::commit();
      return redirect()->back()->with('success', 'Data berhasil disimpan');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error saving pegawai: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
    }
  }

  function delete($id = null, $token = null)
  {
    if (!csrfValidate($token)) {
      return redirect()->back()->with('error', 'Token tidak valid');
    }

    DB::beginTransaction();
    try {
      $delete = DB::table('mst_pegawai')->where('pegawai_id', $id)->update([
        'deleted_st' => 1,
        'deleted_at' => now(),
        'deleted_by' => session('user_id'),
        'active_st' => 0
      ]);

      if (!$delete) {
        throw new \Exception("Gagal menghapus data", 1);
      }

      DB::commit();
      return redirect()->back()->with('success', 'Data berhasil dihapus');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error deleting pegawai: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
  }

  function ajax_datatables()
  {
    $data = PegawaiModel::loadDatatables();
    return $data;
  }

  function sync()
  {
    set_time_limit(300); // Set timeout 5 menit
    
    DB::beginTransaction();
    try {
      $startTime = microtime(true);
      
      // Ambil semua data pegawai dari SIMRS (termasuk yang sudah dihapus)
      $simrsData = DB::connection('simrs')
        ->table('mst_pegawai')
        ->get();

      if ($simrsData->isEmpty()) {
        throw new \Exception("Tidak ada data pegawai di SIMRS");
      }

      $syncedCount = 0;
      $updatedCount = 0;
      $insertedCount = 0;
      $deletedCount = 0;
      $errorCount = 0;
      $errors = [];

      // Ambil semua pegawai_id dari SIMRS untuk tracking
      $simrsIds = $simrsData->pluck('pegawai_id')->toArray();

      foreach ($simrsData as $simrsRow) {
        try {
          $simrsArray = json_decode(json_encode($simrsRow), true);
          $pegawaiId = $simrsRow->pegawai_id;
          
          // Cek apakah data sudah ada di database utama
          $existingData = DB::table('mst_pegawai')
            ->where('pegawai_id', $pegawaiId)
            ->first();

          if ($existingData) {
            // Update data yang sudah ada
            $simrsArray['updated_at'] = now();
            $simrsArray['updated_by'] = session('user_id') ?? 'system';
            
            DB::table('mst_pegawai')
              ->where('pegawai_id', $pegawaiId)
              ->update($simrsArray);
            
            $updatedCount++;
          } else {
            // Insert data baru
            $simrsArray['created_at'] = now();
            $simrsArray['created_by'] = session('user_id') ?? 'system';
            
            DB::table('mst_pegawai')->insert($simrsArray);
            $insertedCount++;
          }
          $syncedCount++;
        } catch (\Exception $e) {
          $errorCount++;
          $errors[] = "ID {$pegawaiId}: " . $e->getMessage();
          Log::warning("Error syncing pegawai_id {$pegawaiId}: " . $e->getMessage());
        }
      }

      // Handle data yang ada di database utama tapi tidak ada di SIMRS (soft delete)
      $placeholders = implode(',', array_fill(0, count($simrsIds), '?'));
      $orphanedData = DB::table('mst_pegawai')
        ->whereRaw("pegawai_id NOT IN ($placeholders)", $simrsIds)
        ->where('deleted_st', 0)
        ->get();

      foreach ($orphanedData as $orphan) {
        try {
          DB::table('mst_pegawai')
            ->where('pegawai_id', $orphan->pegawai_id)
            ->update([
              'deleted_st' => 1,
              'deleted_at' => now(),
              'deleted_by' => 'system_sync',
              'active_st' => 0,
              'updated_at' => now(),
              'updated_by' => 'system_sync'
            ]);
          $deletedCount++;
        } catch (\Exception $e) {
          Log::warning("Error soft deleting orphaned pegawai_id {$orphan->pegawai_id}: " . $e->getMessage());
        }
      }

      $endTime = microtime(true);
      $duration = round(($endTime - $startTime), 2);

      // Buat pesan log
      $logMessage = "Inserted: {$insertedCount}, Updated: {$updatedCount}, Deleted: {$deletedCount}";
      if ($errorCount > 0) {
        $logMessage .= ", Errors: {$errorCount}";
      }
      $logMessage .= ", Duration: {$duration}s";

      // Catat log sinkronisasi
      DB::table('sync_log')->insert([
        'table_name' => 'mst_pegawai',
        'sync_type' => 'full_sync',
        'records_synced' => $syncedCount,
        'status' => $errorCount > 0 ? 'partial_success' : 'success',
        'error_message' => $logMessage,
        'synced_at' => now(),
        'synced_by' => session('user_id') ?? 'system',
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      DB::commit();

      // Buat response message
      $responseMessage = "Sinkronisasi berhasil. Total: {$syncedCount} data (Insert: {$insertedCount}, Update: {$updatedCount}, Delete: {$deletedCount})";
      if ($errorCount > 0) {
        $responseMessage .= ". Terdapat {$errorCount} error. Cek log untuk detail.";
      }
      $responseMessage .= ". Durasi: {$duration} detik";

      return response()->json([
        'success' => true,
        'message' => $responseMessage,
        'data' => [
          'inserted' => $insertedCount,
          'updated' => $updatedCount,
          'deleted' => $deletedCount,
          'errors' => $errorCount,
          'duration' => $duration
        ]
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error syncing pegawai: ' . $e->getMessage());
      
      // Catat log error
      DB::table('sync_log')->insert([
        'table_name' => 'mst_pegawai',
        'sync_type' => 'full_sync',
        'records_synced' => 0,
        'status' => 'error',
        'error_message' => $e->getMessage(),
        'synced_at' => now(),
        'synced_by' => session('user_id') ?? 'system',
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal sinkronisasi data: ' . $e->getMessage()
      ], 500);
    }
  }

  function get_last_sync()
  {
    $lastSync = DB::table('sync_log')
      ->where('table_name', 'mst_pegawai')
      ->where('status', 'success')
      ->orderBy('synced_at', 'desc')
      ->first();

    return response()->json([
      'success' => true,
      'data' => $lastSync
    ]);
  }
}
