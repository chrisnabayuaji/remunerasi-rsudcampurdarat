<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;

class PegawaiModel
{
  public static function loadDatatables()
  {
    $query = "SELECT 
                * 
              FROM (
                SELECT * FROM mst_pegawai WHERE deleted_st = 0
              ) x ";
    $search = ['pegawai_nm', 'nip', 'nama_lengkap', 'jabatan_id', 'unit_id'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
