<?php

namespace App\Modules\App\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\AuthModel;
use App\Modules\Master\Models\IdentitasModel;
use Illuminate\Support\Facades\DB;

class Auth extends Controller
{

  public function login()
  {
    session()->regenerate();
    $d = [
      'title' => 'Login - Sistem Dashboard Klinik',
      'identitas' => IdentitasModel::getIdentitas()
    ];
    return view('app::auth.login', $d);
  }

  public function login_action()
  {
    $request = request();

    $request->validate([
      'u' => 'required',
      'p' => 'required',
    ]);

    try {
      if ($request->session()->token() !== $request->_token) {
        throw new \Exception('Token tidak valid!');
      } else {
        $user = AuthModel::getUser($request->u);
        if (empty($user)) {
          throw new \Exception('Akun tidak ditemukan!');
        } else {
          if ($user['active_st'] == 0) {
            throw new \Exception('Akun tidak aktif!');
          } else {
            if (!empty($user['expired_at']) && now()->gt($user['expired_at'])) {
              throw new \Exception('Akun sudah kadaluarsa!');
            }
            if (password_verify($request->p, $user['user_hash'])) {
              $sessionData = [
                'user_id'   => $user['user_id'],
                'user_nm'   => $user['user_nm'],
                'full_nm'   => $user['full_nm'],
                'role_id'   => $user['role_id'],
                'role_nm'   => $user['role_nm'],
                'login_st'  => true,
                'login_at'  => now()
              ];
              session()->put($sessionData);

              // Update last_login_at
              DB::table('app_user')
                ->where('user_id', $user['user_id'])
                ->update(['last_login_at' => now(), 'updated_at' => now(), 'updated_by' => $user['user_nm']]);

              return redirect()->to('dashboard?n=' . md5('000'));
            } else {
              throw new \Exception('Kombinasi username dan password salah!');
            }
          }
        }
      }
    } catch (\Throwable $th) {
      return redirect()->to('app/auth/login')->with('flash_error', $th->getMessage());
    }
  }

  function logout_action()
  {
    session()->flush();
    return redirect()->to('app/auth/login');
  }
}
