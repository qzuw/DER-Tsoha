<?php

class TeraController extends BaseController{
  public static function index(){
    $terat = Tera::all();
    View::make('listaa_terat.html', array('terat' => $terat));
  }

  public static function nayta($id){
    $tera = Tera::find($id);
    View::make('nayta_tera.html', array('tera' => $tera));
  }

  public static function lisaa(){
    $terat = Tera::all();
    View::make('listaa_terat.html', array('terat' => $terat));
  }
}
