<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class PenjaminModel extends Model
{
  static function loadDatatables()
  {
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*
              FROM mst_penjamin a
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['penjamin_nm', 'ris_id'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
