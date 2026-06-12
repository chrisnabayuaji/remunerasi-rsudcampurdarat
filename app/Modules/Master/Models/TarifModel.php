<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class TarifModel extends Model
{
  protected $table = 'mst_tarif';
  protected $primaryKey = 'tarif_id';
  public $incrementing = false;
  protected $keyType = 'string';

  static function loadDatatables()
  {
    $tarif_parent = session('tarif_parent_filter');
    $filter_sql = "";
    if ($tarif_parent !== null && $tarif_parent !== '') {
      $filter_sql = " AND a.tarif_parent = " . \Illuminate\Support\Facades\DB::connection()->getPdo()->quote($tarif_parent);
    }

    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*
              FROM mst_tarif a
              WHERE
                a.deleted_st = 0
                {$filter_sql}
            ) x ";
    $search = ['tarif_id', 'tarif_nm', 'inacbg_id', 'tarif_cd'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
