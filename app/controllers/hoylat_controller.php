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
        $hoyla = Partahoyla::find($id);
        View::make('nayta_hoyla.html', array('hoyla' => $hoyla));
    }

    public static function uusi() {
        View::make('lisaa_hoyla.html');
    }

    public static function lisaa() {
        $params = $_POST;
        $hoyla = new Partahoyla(array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli']
        ));
        $onnistui = $hoyla->add();
        if ($onnistui) {
            Redirect::to('/nayta_hoyla/' . $hoyla->id, array('message' => 'Partahöylä on nyt lisätty tietokantaan'));
        } else {
            Redirect::to('/listaa_hoylat', array('error' => 'Partahöylän lisääminen tietokantaan epäonnistui, tarkista onko se listassa jo ennestään'));
        }
    }

    public static function poista($id) {
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
