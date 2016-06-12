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
        self::check_logged_in();
        View::make('lisaa_tera.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli'],
            'pehmeys' => 0,
            'teravyys' => 0
        );

        $tera = new Tera($attributes);
        $errors = $tera->errors();

        if (count($errors) == 0) {
            $onnistui = $tera->add();
            if ($onnistui) {
                Redirect::to('/nayta_tera/' . $tera->id, array('message' => 'Terä on nyt lisätty tietokantaan'));
            } else {
                Redirect::to('/listaa_terat', array('error' => 'Terän lisääminen tietokantaan epäonnistui, tarkista onko se listassa jo ennestään'));
            }
        } else {
            Redirect::to('/uusi_tera' . $tera->id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
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
