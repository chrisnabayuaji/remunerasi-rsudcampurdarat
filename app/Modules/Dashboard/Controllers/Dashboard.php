<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\BaseController;

class Dashboard extends BaseController
{

  public function __construct()
  {
    parent::__construct();
    $this->template = 'dashboard::dashboard.';
  }

  public function index()
  {
    $d = [];
    return $this->renderView($this->template . 'index', $d);
  }
}
