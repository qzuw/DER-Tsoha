<?php

class TeraController extends BaseController{
  public static function index(){
    $terat = Tera::all();
    View::make('listaa_terat.html', array('terat' => $terat));
  }
}
