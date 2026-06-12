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
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*,
                b.tarif_nm
              FROM mst_tarif_remunerasi a
              LEFT JOIN mst_tarif b ON a.tarif_id = b.tarif_id
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['tarif_id', 'tarif_nm', 'pelaku_st'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
