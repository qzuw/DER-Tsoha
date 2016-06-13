<?php

class HoylaController extends BaseController {

    public static function index($sivu) {
        $hoylamaara = Partahoyla::count();
        $sivukoko = 10;
        $sivuja = ceil($hoylamaara / $sivukoko);

        if (isset($sivu)) {
            $hoylat = Partahoyla::all(array('sivu' => $sivu));
        } else {
            $hoylat = Partahoyla::all(array());
            $sivu = 1;
        }

        $data = array('hoylat' => $hoylat);

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

        View::make('listaa_hoylat.html', $data);
    }

    public static function nayta($id) {
        $kaytt_id = $_SESSION['tunnus'];
        $kayttaja = Kayttaja::find($kaytt_id);

        $omistaa = false;
        //tanne $omistaa true jos kayttaja omistaa hoylan
        if ($kaytt_id) {
            $omistaa = $kayttaja->omistaako($id);
        }

        $hoyla = Partahoyla::find($id);
        View::make('nayta_hoyla.html', array('hoyla' => $hoyla, 'omistaa' => $omistaa));
    }

    public static function uusi() {
        self::check_logged_in();
        View::make('lisaa_hoyla.html');
    }

    public static function lisaa() {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli'],
            'aggressiivisuus' => 0
        );
        $hoyla = new Partahoyla($attributes);
        $errors = $hoyla->errors();
        if (count($errors) == 0) {
            $onnistui = $hoyla->add();
            if ($onnistui) {
                Redirect::to('/nayta_hoyla/' . $hoyla->id, array('message' => 'Partahöylä on nyt lisätty tietokantaan'));
            } else {
                Redirect::to('/listaa_hoylat', array('error' => 'Partahöylän lisääminen tietokantaan epäonnistui, tarkista onko se listassa jo ennestään'));
            }
        } else {
            Redirect::to('/uusi_hoyla' . $hoyla->id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
        $hoyla = Partahoyla::find($id);
        $hoyla_nimi = $hoyla->valmistaja . " " . $hoyla->malli;
        $onnistui = $hoyla->delete();
        if ($onnistui) {
            Redirect::to('/listaa_hoylat', array('success' => 'Partahöylä ' . $hoyla_nimi . ' on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/nayta_hoyla/' . $id, array('error' => 'Partahöylän poistaminen epäonnistui, se on käytössä'));
        }
    }

}
