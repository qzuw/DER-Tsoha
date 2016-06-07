<?php

class TeraController extends BaseController {

    public static function index($sivu) {
        $teramaara = Tera::count();
        $sivukoko = 10;
        $sivuja = ceil($teramaara / $sivukoko);

        if (isset($sivu)) {
            $terat = Tera::all(array('sivu' => $sivu));
        } else {
            $terat = Tera::all(array());
            $sivu = 1;
        }

        $data = array('terat' => $terat);

        if ($sivu < $sivuja) {
            $seur_sivu = ($sivu + 1);
            $data['seur_sivu'] = $seur_sivu;
        }

        if ($sivu > 1) {
            $ed_sivu = ($sivu - 1);
            $data['ed_sivu'] = $ed_sivu;
        }

        if ($sivuja > 1) {
            $data['nyk_sivu'] = $sivu;
            $data['sivut'] = $sivuja;
        }

        View::make('listaa_terat.html', $data);
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

    public static function poista($id) {
        $tera = Tera::find($id);
        $tera_nimi = $tera->valmistaja . " " . $tera->malli;
        $onnistui = $tera->delete();
        if ($onnistui) {
            Redirect::to('/listaa_terat', array('success' => 'Terä ' . $tera_nimi . ' on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/nayta_tera/' . $id, array('error' => 'Terän poistaminen epäonnistui, se on käytössä'));
        }
    }

}
