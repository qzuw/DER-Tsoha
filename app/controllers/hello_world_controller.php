<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $tera1 = Partahoyla::find(1);
        $terat = Partahoyla::all(array());
        $tera1->pehmeys = 7;
        $tera1->teravyys = 9;
        $tera1->viittauksia = 2;
        $tera1->update();
        $hoyla = new Partahoyla(array(
            'valmistaja' => "Pearl",
            'malli' => "SH-01"
        ));
        $hoyla->add();

        Kint::dump($terat);
        Kint::dump($tera1);
        Kint::dump($hoyla);
    }

    public static function helloworld() {
        View::make('helloworld.html');
    }

    public static function etusivu() {
        View::make('suunnitelmat/etusivu.html');
    }

    public static function kirjaudu() {
        View::make('suunnitelmat/kirjaudu_rekisteroidy.html');
    }

    public static function omat_tiedot() {
        View::make('suunnitelmat/omat_tiedot.html');
    }

    public static function ajopaivakirja() {
        View::make('suunnitelmat/nayta_pvk.html');
    }

    public static function listaa_ajot() {
        View::make('suunnitelmat/listaa_pvkirja.html');
    }

    public static function lisaa_ajopaivakirja() {
        View::make('suunnitelmat/uusi_ajopaivakirja.html');
    }

    public static function lisaa_tera() {
        View::make('suunnitelmat/lisaa_tera.html');
    }

    public static function lisaa_hoyla() {
        View::make('suunnitelmat/lisaa_hoyla.html');
    }

    public static function nayta_tera() {
        View::make('suunnitelmat/nayta_tera.html');
    }

    public static function listaa_terat() {
        View::make('suunnitelmat/listaa_terat.html');
    }

    public static function nayta_hoyla() {
        View::make('suunnitelmat/nayta_hoyla.html');
    }

}
