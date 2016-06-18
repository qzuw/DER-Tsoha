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

    public static function index_user($sivu) {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
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
        $kid = $_SESSION['tunnus'];
        $pvk = Pvk::find($id);
        if (!$pvk->julkisuus && $kid != $pvk->kayttaja->id) {
            $pvk = null;
        }
        $data = array('pvk' => $pvk);
        if (isset($pvk) && $kid == $pvk->kayttaja->id) {
            $data['oma'] = true;
        }     
        View::make('paivakirja/nayta_pvk.html', $data);
    }

    public static function nayta_lisayssivu() {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $data = array();
        $data['omat_hoylat'] = Partahoyla::owned(array('id' => $id, 'maara' => 0));
        $data['hoylat'] = Partahoyla::all(array('maara' => 0));
        $data['terat'] = Tera::all(array('maara' => 0));
        $data['tanaan'] = date("Y-m-d");
        $data['kello'] = date("H:i");
        
        View::make('paivakirja/lisaa_pvk.html', $data);
    }

    public static function lisaa() {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $params = $_POST;
        $hid = $params['hoyla'];
        $tid = $params['tera'];
        if (isset($params['julkisuus']) && $params['julkisuus']) {
            $julkisuus = true;
        } else {
            $julkisuus = 0;
        }
        $attributes = array(
            'kayttaja' => Kayttaja::find($id),
            'hoyla' => Partahoyla::find($hid),
            'tera' => Tera::find($tid),
            'aggressiivisuus' => $params['aggressiivisuus'],
            'teravyys' => $params['teravyys'],
            'pehmeys' => $params['pehmeys'],
            'pvm' => $params['pvm'],
            'klo' => $params['klo'],
            'saippua' => $params['saippua'],
            'kommentit' => $params['ajopvkirja'],
            'julkisuus' => $julkisuus
        );

        $pvk = new Pvk($attributes);
        $errors = $pvk->errors();

        if (count($errors) == 0) {
            $onnistui = $pvk->add();
            if ($onnistui) {
                $hoyla = Partahoyla::find($hid);
                $hoyla->viittauksia = $hoyla->viittauksia + 1;
                $hoyla->aggressiivisuus = $hoyla->aggressiivisuus + $pvk->aggressiivisuus;
                $hoyla->update();
                $tera = Tera::find($tid);
                $tera->viittauksia = $tera->viittauksia + 1;
                $tera->teravyys = $tera->teravyys + $pvk->teravyys;
                $tera->pehmeys = $tera->pehmeys + $pvk->pehmeys;
                $tera->update();
                Redirect::to('/nayta_paivakirja/' . $pvk->id, array('message' => 'Ajopäiväkirjamerkintä on nyt lisätty tietokantaan'));
            } else {
                Redirect::to('/uusi_paivakirja', array('error' => 'Päiväkirjamerkinnän lisääminen tietokantaan epäonnistui', 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/uusi_paivakirja', array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function nayta_muokkaussivu($pid) {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $pvk = Pvk::find($pid);
        if ($id != $pvk->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $pid, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $data = array();
        $data['omat_hoylat'] = Partahoyla::owned(array('id' => $id, 'maara' => 0));
        $data['hoylat'] = Partahoyla::all(array('maara' => 0));
        $data['terat'] = Tera::all(array('maara' => 0));
        $data['pvk'] = Pvk::find($pid);
        
        View::make('paivakirja/muokkaa_pvk.html', $data);
    }

    public static function muokkaa($pid) {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $pvk = Pvk::find($pid);
        if ($id != $pvk->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $pid, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $params = $_POST;
        $hid = $params['hoyla'];
        $tid = $params['tera'];
        $attributes = array(
            'kayttaja' => Kayttaja::find($id),
            'hoyla' => Partahoyla::find($hid),
            'tera' => Tera::find($tid),
            'aggressiivisuus' => $params['aggressiivisuus'],
            'teravyys' => $params['teravyys'],
            'pehmeys' => $params['pehmeys'],
            'pvm' => $params['pvm'],
            'klo' => $params['klo'],
            'saippua' => $params['saippua'],
            'kommentit' => $params['ajopvkirja'],
            'julkisuus' => $julkisuus
        );

        $pvk = new Pvk($attributes);
        $errors = $pvk->errors();

        if (count($errors) == 0) {
            $onnistui = $pvk->update();
            if ($onnistui) {
                //hoylan ja teran arvot voisi refaktoroida nakymiksi jolloin naita muutoksia alla ei tarvita, muuten tama kaipaa viela paivitysta
                $hoyla = Partahoyla::find($hid);
                $hoyla->viittauksia = $hoyla->viittauksia + 1;
                $hoyla->aggressiivisuus = $hoyla->aggressiivisuus + $pvk->aggressiivisuus;
                $hoyla->update();
                $tera = Tera::find($tid);
                $tera->viittauksia = $tera->viittauksia + 1;
                $tera->teravyys = $tera->teravyys + $pvk->teravyys;
                $tera->pehmeys = $tera->pehmeys + $pvk->pehmeys;
                $tera->update();
                Redirect::to('/nayta_paivakirja/' . $pvk->id, array('message' => 'Ajopäiväkirjamerkintä on nyt päivitetty tietokantaan'));
            } else {
                Redirect::to('/muokkaa_paivakirja/' . $pvk->id, array('error' => 'Päiväkirjamerkinnän muokkaaminen epäonnistui', 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/muokkaa_paivakirja/' . $pvk->id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function poista($id) {
        self::check_logged_in();
        $kid = $_SESSION['tunnus'];
        $pvk = Pvk::find($id);
        if ($kid != $pvk->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $id, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $pvk_aika = $pvk->pvm . " " . $pvk->klo;
        $onnistui = $pvk->delete();
        if ($onnistui) {
            Redirect::to('/listaa_omat_paivakirjat', array('success' => 'Ajopäiväkirjan merkintä ' . $pvk_aika . ' on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/nayta_paivakirja/' . $id, array('error' => 'Ajopäiväkirjamerkinnän poistaminen epäonnistui'));
        }
    }

}
