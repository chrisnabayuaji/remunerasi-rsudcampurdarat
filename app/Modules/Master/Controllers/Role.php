<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\RoleModel;
use Illuminate\Support\Facades\Log;

class Role extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::role.';
  }

  function index()
  {
    $d = [];
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DbModel::getData('app_role', ['role_id' => $id]);
    $d['form_act'] = $this->nav_url . '/save' . ($id ? '/' . $id : '') . '?n=' . $this->nav_id;
    return $this->renderView($this->template . 'formModal', $d);
  }

  function save($id = null)
  {
    $d = fsPost();

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        $insertData = DbModel::insertData('app_role', $d);
        if (!$insertData) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('app_role', $d, ['role_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $message = "Data sukses diubah!";
      }
      DbModel::commitTransaction();
      return redirect()
        ->to('master/role?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/role?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function delete($id, $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('app_role', ['role_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/role?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/role?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return RoleModel::loadDatatables();
  }

  function permission_modal($id)
  {
    $d['main'] = DbModel::getData('app_role', ['role_id' => $id]);
    $d['all_nav'] = $this->getAllNav();
    
    // Get existing role permissions
    $role_nav = DbModel::allData('app_role_nav', ['role_id' => $id], ['nav_id', 'ASC']);
    $d['role_nav_ids'] = array_column($role_nav, 'nav_id');
    
    $d['form_act'] = $this->nav_url . '/save_permission/' . $id . '?n=' . $this->nav_id;
    return $this->renderView($this->template . 'permissionModal', $d);
  }

  private function getAllNav($parent_id = null)
  {
    $params = ['active_st' => 1];
    if ($parent_id === null) {
        // Need to check for both null and empty string depending on DB content
        $res = DbModel::rawData('result_array', "SELECT * FROM app_nav WHERE active_st = 1 AND (parent_id IS NULL OR parent_id = '') ORDER BY nav_id ASC");
    } else {
        $res = DbModel::allData('app_nav', ['active_st' => 1, 'parent_id' => $parent_id], ['nav_id', 'ASC']);
    }
    
    if ($res != null) {
      foreach ($res as $key => $val) {
        $res[$key]['child'] = $this->getAllNav($res[$key]['nav_id']);
      }
      return $res;
    } else {
      return array();
    }
  }

  function save_permission($id)
  {
    $d = fsPost();
    $nav_ids = $d['nav_ids'] ?? [];

    DbModel::beginTransaction();
    try {
      // Clear existing permissions
      DbModel::deleteData('app_role_nav', ['role_id' => $id], false);

      // Insert new permissions
      foreach ($nav_ids as $nav_id) {
        DbModel::insertData('app_role_nav', [
          'role_id' => $id,
          'nav_id' => $nav_id
        ]);
      }

      DbModel::commitTransaction();
      return redirect()
        ->to('master/role?n=' . $this->nav_id)
        ->with('flash_success', 'Permission berhasil diperbarui!');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      return redirect()
        ->to('master/role?n=' . $this->nav_id)
        ->with('flash_error', 'Gagal memperbarui permission!');
    }
  }
}
