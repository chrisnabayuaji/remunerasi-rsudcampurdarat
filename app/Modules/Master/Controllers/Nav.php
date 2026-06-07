<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\NavModel;
use Illuminate\Support\Facades\Log;

class Nav extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::nav.';
  }

  function index()
  {
    $d = [];
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DbModel::getData('app_nav', ['nav_id' => $id]);
    $d['all_data'] = DbModel::allData('app_nav', null, ['nav_id', 'ASC']);
    $d['all_module'] = DbModel::allData('app_nav', ['module_st' => 1], ['nav_id', 'ASC']);
    $d['form_act'] = $this->nav_url . '/save' . ($id ? '/' . $id : '') . '?n=' . $this->nav_id;
    return $this->renderView($this->template . 'formModal', $d);
  }

  function save($id = null)
  {
    $d = fsPost();

    // Check Duplicate Nav ID
    $check = DbModel::getData('app_nav', ['nav_id' => $d['nav_id']]);
    if ($check && $d['nav_id'] != $id) {
      return redirect()
        ->back()
        ->with('flash_error', "Nav id '{$d['nav_id']}' sudah digunakan oleh menu lain!");
    }

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        $insertData = DbModel::insertData('app_nav', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        // Auto save untuk superadmin ke app_nav_role
        $insertSuperAdmin = DbModel::insertOrIgnoreData('app_role_nav', ['role_id' => 1, 'nav_id' => $d['nav_id']]);
        if (!$insertSuperAdmin) {
          throw new \Exception("Gagal menyimpan data superadmin", 1);
        }
        //
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('app_nav', $d, ['nav_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        // Auto save untuk superadmin ke app_nav_role
        $insertSuperAdmin = DbModel::insertOrIgnoreData('app_role_nav', ['role_id' => 1, 'nav_id' => $d['nav_id']]);
        if (!$insertSuperAdmin) {
          throw new \Exception("Gagal menyimpan data superadmin", 1);
        }
        //
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/nav?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/nav?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete($id, $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('app_nav', ['nav_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/nav?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/nav?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return NavModel::loadDatatables();
  }
}
