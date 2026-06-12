<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DbModel;

class KomponenTarifModel extends Model
{
  protected $table = 'mst_komponen_tarif';
  protected $primaryKey = 'komponentarif_id';
  public $incrementing = false;
  protected $keyType = 'string';

  static function loadDatatables()
  {
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*,
                b.komponentarif_nm AS parent_nm
              FROM mst_komponen_tarif a
              LEFT JOIN mst_komponen_tarif b ON a.komponentarif_parent = b.komponentarif_id
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['komponentarif_id', 'komponentarif_nm', 'parent_nm'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
