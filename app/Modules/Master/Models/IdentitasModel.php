<?php

namespace App\Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;

class IdentitasModel extends Model
{
  protected $table = 'app_identitas';
  protected $primaryKey = 'identitas_id';
  
  static function getIdentitas()
  {
    return self::first();
  }
}
