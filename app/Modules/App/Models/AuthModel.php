<?php

namespace App\Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthModel extends Model
{
  static function getUser(string $user_nm)
  {
    $user = DB::table('app_user as u')
      ->leftJoin('app_user_role as ur', function ($join) {
        $join->on('u.user_id', '=', 'ur.user_id')
             ->where('ur.utama_st', '=', 1)
             ->where('ur.deleted_st', '=', 0);
      })
      ->leftJoin('app_role as r', 'ur.role_id', '=', 'r.role_id')
      ->where('u.user_nm', $user_nm)
      ->where('u.deleted_st', 0)
      ->select(
        'u.user_id',
        'u.user_nm',
        'u.full_nm',
        'u.user_hash',
        'u.active_st',
        'u.expired_at',
        'ur.role_id',
        'r.role_nm'
      )
      ->first();

    return $user ? json_decode(json_encode($user), true) : null;
  }
}
