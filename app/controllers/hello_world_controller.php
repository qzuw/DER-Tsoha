<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
    }

    public static function etusivu() {
        // Testaa koodiasi täällä
        View::make('etusivu.html');
    }

    public static function kirjaudu() {
        // Testaa koodiasi täällä
        View::make('kirjaudu_rekisteroidy.html');
    }

    public static function omat_tiedot() {
        // Testaa koodiasi täällä
        View::make('omat_tiedot.html');
    }

    public static function ajopaivakirja() {
        // Testaa koodiasi täällä
        View::make('ajopaivakirja.html');
    }

    public static function lisaa_ajopaivakirja() {
        // Testaa koodiasi täällä
        View::make('uusi_ajopaivakirja.html');
    }

    public static function lisaa_tera() {
        // Testaa koodiasi täällä
        View::make('lisaa_tera.html');
    }

    public static function lisaa_hoyla() {
        // Testaa koodiasi täällä
        View::make('lisaa_hoyla.html');
    }

}
