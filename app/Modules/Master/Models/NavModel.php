<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class NavModel extends Model
{
  static function loadDatatables()
  {
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*
              FROM app_nav a
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['nav_id', 'nav_nm'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
