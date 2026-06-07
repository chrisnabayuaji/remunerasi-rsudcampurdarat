<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class UserModel extends Model
{
  static function loadDatatables()
  {
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*,
                ur.role_id,
                b.role_nm
              FROM app_user a
              LEFT JOIN app_user_role ur ON a.user_id = ur.user_id AND ur.utama_st = 1 AND ur.deleted_st = 0
              LEFT JOIN app_role b ON ur.role_id = b.role_id
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['user_nm', 'full_nm', 'role_nm'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
