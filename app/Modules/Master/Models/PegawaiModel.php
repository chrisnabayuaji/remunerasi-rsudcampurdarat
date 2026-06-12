<?php

namespace App\Modules\Master\Models;

use App\Models\DbModel;

class PegawaiModel
{
  public static function loadDatatables()
  {
    $query = "SELECT 
                x.*
              FROM (
                SELECT 
                  a.*,
                  b.jabatan_nm,
                  c.lokasi_nm AS unit_nm
                FROM mst_pegawai a
                LEFT JOIN mst_jabatan b ON a.jabatan_id = b.jabatan_id
                LEFT JOIN mst_lokasi c ON a.lokasi_id = c.lokasi_id
                WHERE a.deleted_st = 0
              ) x ";
    $search = ['pegawai_nm', 'nip', 'nama_lengkap', 'jabatan_nm', 'unit_nm'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
