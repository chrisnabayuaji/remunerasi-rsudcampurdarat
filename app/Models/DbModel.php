<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DbModel extends Model
{
  static function allData(String $table, $params = array(), $order = array())
  {
    try {
      $query = DB::table($table);
      if (!empty($params)) {
        if (is_string($params)) {
          $query->whereRaw($params);
        } else {
          $query->where($params);
        }
      }
      
      if (!empty($order)) {
        $query->orderBy(...$order);
      }
      $result = $query->get()->all();
      return json_decode(json_encode($result), true);
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  static function getData(String $table, $params = array())
  {
    try {
      $query = DB::table($table);
      if (!empty($params)) {
        if (is_string($params)) {
          $query->whereRaw($params);
        } else {
          $query->where($params);
        }
      }
      $result = $query->first();
      return json_decode(json_encode($result), true);
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  static function rawData(String $init, String $query, Array $params = [])
  {
    try {
      $result = null;
      switch ($init) {
        case 'result_array':
          $result = DB::select($query, $params);
          break;

        case 'row_array':
          $result = DB::selectOne($query, $params);
          break;

        case 'row':
          $result = DB::selectOne($query, $params);
          break;

        case 'num_rows':
          $result = DB::select($query, $params);
          break;

        default:
          $result = DB::selectOne($query, $params);
          break;
      }
      return json_decode(json_encode($result), true);
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return null;
    }
  }

  public static function insertData(String $table, Array $data)
  {
    if (!isset($data['created_at'])) {
      $data['created_at'] = date('Y-m-d H:i:s');
    }
    if (!isset($data['created_by'])) {
      $data['created_by'] = session('user_id');
    }
    try {
      DB::table($table)->insert($data);
      return true;
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  public static function insertGetIdData(String $table, Array $data)
  {
    if (!isset($data['created_at'])) {
      $data['created_at'] = date('Y-m-d H:i:s');
    }
    if (!isset($data['created_by'])) {
      $data['created_by'] = session('user_id');
    }
    try {
      return DB::table($table)->insertGetId($data);
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  public static function insertOrIgnoreData(String $table, Array $data)
  {
    if (!isset($data['created_at'])) {
      $data['created_at'] = date('Y-m-d H:i:s');
    }
    if (!isset($data['created_by'])) {
      $data['created_by'] = session('user_id');
    }
    try {
      DB::table($table)->insertOrIgnore($data);
      return true;
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  public static function updateData(String $table, Array $data, Array $where)
  {
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['updated_by'] = session('user_id');

    try {
      DB::table($table)->where($where)->update($data);
      return true;
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  public static function deleteData(String $table, Array $where, $soft = true)
  {
    try {
      if ($soft) {
        $data = [
          'deleted_at' => date('Y-m-d H:i:s'),
          'deleted_by' => session('user_id'),
          'deleted_st' => 1,
          'active_st' => 0,
        ];
        DB::table($table)->where($where)->update($data);
      } else {
        DB::table($table)->where($where)->delete();
      }
      return true;
    } catch (\Throwable $th) {
      report($th);

      if (app()->environment('local')) {
        throw $th;
      }

      return false;
    }
  }

  public static function beginTransaction()
  {
    DB::beginTransaction();
  }

  public static function commitTransaction()
  {
    DB::commit();
  }

  public static function rollbackTransaction()
  {
    DB::rollBack();
  }

  public static function datatablesQuery(String $query, Array $keyword, Array $where, $isWhere = null)
  {
    // Params
    $d = fsPost();

    $_search_value = @$d['search']['value'];
    $_length = @$d['length'];
    $_start = @$d['start'];
    $_order_field = @$d['order'][0]['column'];
    $_order_ascdesc = @$d['order'][0]['dir'];

    //
    // Ambil data yang diketik user pada textbox pencarian
    $search = htmlspecialchars($_search_value);
    $search = strtolower($search);
    //
    // Ambil data limit per page
    $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_length}");
    //
    // Ambil data start 
    $start = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_start}");
    //
    // Lower keyword
    if (is_array($keyword)) {
      foreach ($keyword as $k => $v) {
        $keyword[$k] = "LOWER(" . $v . ")";
      }
    }

    $strWhere = " WHERE ";

    if ($isWhere != null) {
      if (strtolower(substr(@$isWhere, 0, 3)) == "and" || @$isWhere == "") {
        $strWhere .= "1 = 1 ";
      } else {
        $strWhere .= " ";
      }

      $strWhere .= $isWhere;
    } else {
      $strWhere .= "1 = 1 ";
    }

    if ($where != null) {
      $setWhere = array();
      foreach ($where as $key => $value) {
        $setWhere[] = $key . "='" . $value . "'";
      }
      $fwhere = implode(' AND ', $setWhere);
      $strWhere .= " AND " . $fwhere;
    }

    // Untuk mengambil nama field yang menjadi acuan untuk sorting
    $strOrder = " ORDER BY " . @$d['columns'][$_order_field]['data'] . " " . $_order_ascdesc;

    $queryData = $query . $strWhere;
    $queryAllRecord = strReplaceBetween($queryData, 'SELECT', 'FROM', ' COUNT(1) AS count ');

    // Searching by keyword
    if ($keyword != null && @count($keyword) > 0) {
      $strWhereKeyword = $strWhere;
      $strKeyword = implode(" LIKE '%" . $search . "%' OR ", $keyword) . " LIKE '%" . $search . "%'";
      $strWhereKeyword .= " AND (" . $strKeyword . ") ";

      $queryData = $query . $strWhereKeyword . $strOrder;
      $queryFiltered = $query . $strWhereKeyword;
    } else {
      $queryData = $query . $strWhere . $strOrder;
      $queryFiltered = $query . $strWhere;
    }

    $queryData .= " LIMIT " . $limit . " OFFSET " . $start;

    $data = self::rawData('result_array', $queryData);
    $recordsTotal = self::rawData('row_array', $queryAllRecord)['count'];

    if ($keyword != null && count($keyword) > 0) {
      $queryRecordsFiltered = strReplaceBetween($queryFiltered, 'SELECT', 'FROM', ' COUNT(1) AS count ');
      $recordsFiltered = self::rawData('row_array', $queryRecordsFiltered)['count'];
    } else {
      $recordsFiltered = $recordsTotal;
    }

    $callback = array(
      'draw' => $d['draw'],
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    return $callback;
  }
}
