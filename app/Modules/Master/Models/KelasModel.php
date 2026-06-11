<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class KelasModel extends Model
{
  static function loadDatatables()
  {
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*
              FROM mst_kelas a
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['kelas_nm', 'kelas_singkatan', 'kelas_bpjs', 'kelas_eklaim'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
