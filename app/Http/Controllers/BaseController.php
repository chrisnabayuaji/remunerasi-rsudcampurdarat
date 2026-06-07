<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\AppModel;
use App\Modules\Master\Models\IdentitasModel;

class BaseController extends Controller
{

  var $identitas;
  var $nav, $nav_id, $nav_sess;
  var $module_title, $parent_title, $menu_title;
  var $nav_url, $nav_icon, $parent_id;
  var $search_act, $template;

  public function __construct()
  {
    $this->identitas = IdentitasModel::getIdentitas();
    // Request
    $request = request();

    // Nav
    $this->nav_id = $request->query('n');
    $this->nav = AppModel::getNav($this->nav_id);
    $this->parent_id = $this->nav['parent_id'];
    $this->module_title = $this->nav['module_nm'];
    $this->parent_title = $this->nav['parent_nm'];
    $this->menu_title = $this->nav['nav_nm'];
    $this->nav_url = url($this->nav['nav_url']);
    $this->nav_icon = $this->nav['nav_icon'];
    $this->search_act = url('app/search_init?n=' . $this->nav_id);
  }

  function renderView(String $content, array $data = [])
  {
    $request = request();

    $data['identitas'] = $this->identitas;
    $data['nav_list'] = AppModel::listNav();
    $data['template'] = $this->template;
    $data['nav_id'] = $this->nav_id;
    $data['nav'] = $this->nav;
    $data['module_title'] = $this->module_title;
    $data['parent_title'] = $this->parent_title ?? $this->module_title;
    $data['menu_title'] = $this->menu_title;
    $data['nav_url'] = $this->nav_url;
    $data['nav_icon'] = $this->nav_icon;
    $data['search_act'] = $this->search_act;
    $data['nav_sess'] = session($request->get('n'));
    $data['content'] = $content;

    if ($request->query('_ajax_st')) {
      return View($content, $data)->render();
    } else {
      return View('app::template.index', $data);
    }
  }
}
