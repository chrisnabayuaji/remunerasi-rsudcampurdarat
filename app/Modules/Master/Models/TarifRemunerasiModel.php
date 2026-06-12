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
    $nav_id = request()->query('n');
    $sess = session($nav_id);
    $tarif_parent = $sess['tarif_tp'] ?? '';
    $filter_sql = "";
    if ($tarif_parent !== null && $tarif_parent !== '') {
      $escaped_parent = \Illuminate\Support\Facades\DB::connection()->getPdo()->quote($tarif_parent . '%');
      $filter_sql .= " AND a.tarif_id LIKE {$escaped_parent}";
    }

    $search_filter = $sess['search'] ?? '';
    if ($search_filter !== null && $search_filter !== '') {
      $escaped_search = \Illuminate\Support\Facades\DB::connection()->getPdo()->quote('%' . strtolower($search_filter) . '%');
      $filter_sql .= " AND (LOWER(a.tarif_id) LIKE {$escaped_search} OR LOWER(b.tarif_nm) LIKE {$escaped_search} OR LOWER(b.tarif_cd) LIKE {$escaped_search})";
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
