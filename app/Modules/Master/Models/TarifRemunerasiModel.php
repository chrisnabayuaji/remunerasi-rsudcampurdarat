<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class TarifRemunerasiModel extends Model
{
  protected $table = 'mst_tarif_remunerasi';
  protected $primaryKey = 'id';
  static function loadDatatables()
  {
    $tarif_parent = session('tarif_remunerasi_parent_filter');
    $filter_sql = "";
    if ($tarif_parent !== null && $tarif_parent !== '') {
      $filter_sql = " AND b.tarif_parent = " . \Illuminate\Support\Facades\DB::connection()->getPdo()->quote($tarif_parent);
    }

    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*,
                b.tarif_nm,
                b.tarif_tp,
                b.nominal,
                b.unit_cost
              FROM mst_tarif_remunerasi a
              LEFT JOIN mst_tarif b ON a.tarif_id = b.tarif_id
              WHERE
                a.deleted_st = 0
                {$filter_sql}
            ) x ";
    $search = ['tarif_id', 'tarif_nm', 'pelaku_st'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
