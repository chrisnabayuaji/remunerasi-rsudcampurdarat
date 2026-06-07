<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\DbModel;
use App\Modules\Master\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->template = 'master::user.';
  }

  function index()
  {
    $d = [];
    return $this->renderView($this->template . 'index', $d);
  }

  function form_modal($id = null)
  {
    $d['main'] = DbModel::getData('app_user', ['user_id' => $id]);
    if ($id && $d['main']) {
      // Ambil role utama dari app_user_role
      $userRole = DbModel::getData('app_user_role', [
        ['user_id', '=', $id],
        ['utama_st', '=', 1],
        ['deleted_st', '=', 0],
      ]);
      $d['main']['role_id'] = $userRole['role_id'] ?? null;
    }
    $d['all_role'] = DbModel::allData('app_role', ['active_st' => 1], ['role_nm', 'ASC']);
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
    $role_id = $d['role_id'] ?? null;
    unset($d['role_id']); // role_id tidak disimpan langsung di app_user

    // Check Duplicate Username (user_nm)
    $checkUser = DbModel::getData('app_user', ['user_nm' => $d['user_nm']]);
    if ($checkUser && $checkUser['user_id'] != $id) {
       return redirect()
         ->back()
         ->with('flash_error', "Username '{$d['user_nm']}' sudah digunakan oleh user lain!");
    }

    // Password Hashing
    if (isset($d['password']) && !empty($d['password'])) {
      if ($d['password'] !== $d['password_confirmation']) {
        return redirect()
          ->back()
          ->with('flash_error', "Konfirmasi password tidak cocok!");
      }
      $d['user_hash'] = password_hash($d['password'], PASSWORD_DEFAULT);
    }
    unset($d['password'], $d['password_confirmation']);

    DbModel::beginTransaction();
    try {
      if ($id == null) {
        if (!isset($d['user_hash'])) {
           throw new \Exception("Password wajib diisi untuk user baru!", 1);
        }
        $newUserId = DbModel::insertGetIdData('app_user', $d);
        if (!$newUserId) {
          throw new \Exception("Gagal menyimpan data", 1);
        }
        $message = "Data sukses ditambah!";
      } else {
        $updateData = DbModel::updateData('app_user', $d, ['user_id' => $id]);
        if (!$updateData) {
          throw new \Exception("Gagal mengubah data", 1);
        }
        $newUserId = $id;
        $message = "Data sukses diubah!";
      }

      // Simpan / Update role utama di app_user_role
      if ($role_id) {
        // Soft-delete role lama
        DB::table('app_user_role')
          ->where('user_id', $newUserId)
          ->where('utama_st', 1)
          ->update([
            'deleted_st' => 1,
            'deleted_at' => now(),
            'deleted_by' => session('user_nm'),
            'active_st'  => 0,
          ]);

        // Insert role baru
        DB::table('app_user_role')->insert([
          'user_id'    => $newUserId,
          'role_id'    => $role_id,
          'utama_st'   => 1,
          'active_st'  => 1,
          'deleted_st' => 0,
          'created_at' => now(),
          'created_by' => session('user_nm') ?? 'System',
        ]);
      }

      DbModel::commitTransaction();
      return redirect()
        ->to('master/user?n=' . $this->nav_id)
        ->with('flash_success', $message);
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/user?n=' . $this->nav_id)
        ->with('flash_error', $th->getMessage());
    }
  }

  function delete($id, $token)
  {
    try {
      if (session()->token() !== $token) {
        throw new \Exception("Token tidak valid", 1);
      }
      $deleteData = DbModel::deleteData('app_user', ['user_id' => $id], false);
      if (!$deleteData) {
        throw new \Exception("Gagal menghapus data", 1);
      }
      return redirect()->to('master/user?n=' . $this->nav_id)->with('flash_success', 'Data sukses dihapus');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->to('master/user?n=' . $this->nav_id)
        ->with('flash_error', 'Terjadi kesalahan sistem. Silakan coba lagi!');
    }
  }

  function ajax_datatables()
  {
    return UserModel::loadDatatables();
  }

  function profile_modal()
  {
    $id = session('user_id');
    $d['main'] = DbModel::getData('app_user', ['user_id' => $id]);
    $d['form_act'] = url('master/user/save_profile?n=' . $this->nav_id);
    return $this->renderView($this->template . 'profileModal', $d);
  }

  function save_profile()
  {
    $id = session('user_id');
    $d = fsPost();

    // Check Duplicate Username (user_nm)
    $checkUser = DbModel::getData('app_user', ['user_nm' => $d['user_nm']]);
    if ($checkUser && $checkUser['user_id'] != $id) {
       return redirect()
         ->back()
         ->with('flash_error', "Username '{$d['user_nm']}' sudah digunakan oleh user lain!");
    }

    // Password Hashing
    if (isset($d['password']) && !empty($d['password'])) {
      if ($d['password'] !== $d['password_confirmation']) {
        return redirect()
          ->back()
          ->with('flash_error', "Konfirmasi password tidak cocok!");
      }
      $d['user_hash'] = password_hash($d['password'], PASSWORD_DEFAULT);
    }
    unset($d['password'], $d['password_confirmation']);

    DbModel::beginTransaction();
    try {
      $updateData = DbModel::updateData('app_user', $d, ['user_id' => $id]);
      if (!$updateData) {
        throw new \Exception("Gagal mengubah data profil", 1);
      }

      // Update session values
      session()->put('full_nm', $d['full_nm']);
      session()->put('user_nm', $d['user_nm']);

      DbModel::commitTransaction();
      return redirect()
        ->back()
        ->with('flash_success', 'Profil Anda sukses diperbarui!');
    } catch (\Throwable $th) {
      DbModel::rollbackTransaction();
      Log::error($th->getMessage());
      if (app()->environment('local')) {
        throw $th;
      }
      return redirect()
        ->back()
        ->with('flash_error', $th->getMessage());
    }
  }
}
