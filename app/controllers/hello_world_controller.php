<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $tera1 = Tera::find(1);
        $terat = Tera::all();
        $tera2 = new Tera(array('valmistaja' => 'Bolzano', 'malli' => 'superinox'));
        $tulos = $tera2->add();
        if ($tulos) {
            echo "tulos true";
        }
        if (!$tulos) {
            echo "tulos false";
        }

        Kint::dump($terat);
        Kint::dump($tera1);
    }

    public static function helloworld() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
    }

    public static function etusivu() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/etusivu.html');
    }

    public static function kirjaudu() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/kirjaudu_rekisteroidy.html');
    }

    public static function omat_tiedot() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/omat_tiedot.html');
    }

    public static function ajopaivakirja() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/nayta_pvk.html');
    }

    public static function listaa_ajot() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/listaa_pvkirja.html');
    }

    public static function lisaa_ajopaivakirja() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/uusi_ajopaivakirja.html');
    }

    public static function lisaa_tera() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/lisaa_tera.html');
    }

    public static function lisaa_hoyla() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/lisaa_hoyla.html');
    }

    public static function nayta_tera() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/nayta_tera.html');
    }

    public static function nayta_hoyla() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/nayta_hoyla.html');
    }

}
