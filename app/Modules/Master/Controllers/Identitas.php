<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\IdentitasModel;
use Illuminate\Support\Facades\Log;

class Identitas extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::identitas.';
  }

  function index()
  {
    $d['main'] = IdentitasModel::getIdentitas() ?? [];
    $d['form_act'] = $this->nav_url . '/save?n=' . $this->nav_id;
    return $this->renderView($this->template . 'index', $d);
  }

  function save()
  {
    $d = fsPost();
    
    // Check if identitas exists
    $identitas = IdentitasModel::getIdentitas();
    $id = $identitas->identitas_id ?? null;

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        $insertData = DbModel::insertData('app_identitas', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data identitas sukses disimpan!";
      } else {
        $updateData = DbModel::updateData('app_identitas', $d, ['identitas_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data identitas sukses diperbarui!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/identitas?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/identitas?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }
}
