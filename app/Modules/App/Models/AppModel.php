<?php

namespace App\Modules\App\Models;

use App\Models\DbModel;
use Illuminate\Database\Eloquent\Model;

class AppModel extends Model
{
  static function listNav($parent_id = null)
  {
    $role_id = session('role_id');
    $where = ($parent_id != '') ? "b.parent_id = '$parent_id' " : "(b.parent_id = '' OR b.parent_id IS NULL) ";
    $sql = "SELECT
              a.role_id,
              a.nav_id,
              b.parent_id,
              b.module_st,
              b.nav_nm,
              b.nav_url,
              b.nav_icon
            FROM app_role_nav a
            JOIN app_nav b ON a.nav_id = b.nav_id
            WHERE
              $where
              AND b.active_st = 1
              AND a.role_id = ?
            ORDER BY a.nav_id";
    $res = DbModel::rawData('result_array', $sql, [$role_id]);
    if ($res != null) {
      foreach ($res as $key => $val) {
        $res[$key]['child'] = AppModel::listNav($res[$key]['nav_id']);
      }
      return $res;
    } else {
      return array();
    }
  }

  static function getNav(String $nav_id)
  {
    $role_id = session('role_id');
    $nav_id = e($nav_id);

    $sql = "SELECT
              a.nav_id,
              a.parent_id,
              a.nav_nm,
              c.nav_nm AS parent_nm,
              d.nav_nm AS module_nm,
              a.nav_url,
              a.nav_icon
            FROM app_nav a
            JOIN app_role_nav b ON a.nav_id = b.nav_id
            LEFT JOIN app_nav c ON a.parent_id = c.nav_id
            LEFT JOIN app_nav d ON a.module_id = d.nav_id
            WHERE
              b.role_id = ?
              AND md5(a.nav_id) = ?";
    $res = DbModel::rawData('row_array', $sql, [$role_id, $nav_id]);
    return $res;
  }
}
