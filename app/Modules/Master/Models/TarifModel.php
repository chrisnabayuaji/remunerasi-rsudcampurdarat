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
    $query = "SELECT 
              x.*
            FROM (
              SELECT 
                a.*
              FROM mst_tarif a
              WHERE
                a.deleted_st = 0
            ) x ";
    $search = ['tarif_id', 'tarif_nm', 'inacbg_id', 'tarif_cd'];
    $where = [];
    $isWhere = null;

    $result = DbModel::datatablesQuery($query, $search, $where, $isWhere);
    return response()->json($result);
  }
}
