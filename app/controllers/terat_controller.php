<?php

class TeraController extends BaseController {

    public static function index() {
        $terat = Tera::all();
        View::make('listaa_terat.html', array('terat' => $terat));
    }

    public static function nayta($id) {
        $tera = Tera::find($id);
        View::make('nayta_tera.html', array('tera' => $tera));
    }

    public static function uusi() {
        View::make('lisaa_tera.html');
    }

    public static function lisaa() {
        $params = $_POST;
        $tera = new Tera(array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli']
        ));
        $onnistui = $tera->add();
        if ($onnistui) {
            Redirect::to('/nayta_tera/' . $tera->id, array('message' => 'Terä on nyt lisätty tietokantaan'));
        } else {
            Redirect::to('/listaa_terat', array('error' => 'Terän lisääminen tietokantaan epäonnistui, tarkista onko se listassa jo ennestään'));
        }
    }

}
