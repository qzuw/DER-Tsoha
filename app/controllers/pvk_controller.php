<?php

class PvkController extends BaseController {

    public static function index($sivu) {
        self::check_logged_in();
        $pvkmaara = Pvk::count();
        $sivukoko = 10;
        $sivuja = ceil($pvkmaara / $sivukoko);

        if (isset($sivu)) {
            $pvkt = Pvk::all(array('sivu' => $sivu));
        } else {
            $pvkt = Pvk::all(array());
            $sivu = 1;
        }

        $data = array('pvk' => $pvkt);

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

        View::make('paivakirja/listaa_pvk.html', $data);
    }

    public static function index_user($id, $sivu) {
        self::check_logged_in();
        $pvkmaara = Pvk::count_user($id);
        $sivukoko = 10;
        $sivuja = ceil($pvkmaara / $sivukoko);

        if (isset($sivu)) {
            $pvkt = Pvk::all_user(array('sivu' => $sivu, 'kayttaja' => $id));
        } else {
            $pvkt = Pvk::all(array('kayttaja' => $id));
            $sivu = 1;
        }

        $data = array('pvk' => $pvkt);

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

        View::make('paivakirja/listaa_oma_pvk.html', $data);
    }

    public static function nayta($id) {
        self::check_logged_in();
        $pvk = Pvk::find($id);
        View::make('paivakirja/nayta_pvk.html', array('pvk' => $pvk));
    }

    public static function uusi() {
        self::check_logged_in();
        View::make('paivakirja/lisaa_pvk.html');
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
