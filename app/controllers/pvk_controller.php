<?php

class PvkController extends BaseController {

    public static function index($page_number) {
        self::check_logged_in();
        $diary_amount = Pvk::count();
        $page_size = 10;
        $num_pages = ceil($diary_amount / $page_size);

        if (isset($page_number)) {
            $diaries = Pvk::all(array('sivu' => $page_number));
        } else {
            $diaries = Pvk::all(array());
            $page_number = 1;
        }

        $data = array('pvk' => $diaries);

        $data = array_merge($data, self::sivutus($page_number, $num_pages));

        View::make('paivakirja/listaa_pvk.html', $data);
    }

    public static function index_user($page) {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $diary_amount = Pvk::count_user($user_id);
        $page_size = 10;
        $num_pages = ceil($diary_amount / $page_size);

        if (isset($page)) {
            $diaries = Pvk::all_user(array('sivu' => $page, 'kayttaja' => $user_id));
        } else {
            $diaries = Pvk::all(array('kayttaja' => $user_id));
            $page = 1;
        }

        $data = array('pvk' => $diaries);

        $data = array_merge($data, self::sivutus($page, $num_pages));

        View::make('paivakirja/listaa_oma_pvk.html', $data);
    }

    public static function nayta($id) {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $diary = Pvk::find($id);
        if (!$diary->julkisuus && $user_id != $diary->kayttaja->id) {
            $diary = null;
        }
        $data = array('pvk' => $diary);
        if (isset($diary) && $user_id == $diary->kayttaja->id) {
            $data['oma'] = true;
        }
        View::make('paivakirja/nayta_pvk.html', $data);
    }

    public static function nayta_lisayssivu() {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $data = array();
        $data['omat_hoylat'] = Partahoyla::owned(array('id' => $user_id, 'maara' => 0));
        $data['hoylat'] = Partahoyla::all(array('maara' => 0));
        $data['terat'] = Tera::all(array('maara' => 0));
        $data['tanaan'] = date("Y-m-d");
        $data['kello'] = date("H:i");

        View::make('paivakirja/lisaa_pvk.html', $data);
    }

    public static function lisaa() {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $params = $_POST;
        $razor_id = $params['hoyla'];
        $blade_id = $params['tera'];
        if (isset($params['julkisuus']) && $params['julkisuus'] == "on") {
            $is_public = true;
        } else {
            $is_public = 0;
        }
        $attributes = array(
            'kayttaja' => Kayttaja::find($user_id),
            'hoyla' => Partahoyla::find($razor_id),
            'tera' => Tera::find($blade_id),
            'aggressiivisuus' => $params['aggressiivisuus'],
            'teravyys' => $params['teravyys'],
            'pehmeys' => $params['pehmeys'],
            'pvm' => $params['pvm'],
            'klo' => $params['klo'],
            'saippua' => $params['saippua'],
            'kommentit' => $params['ajopvkirja'],
            'julkisuus' => $is_public
        );

        $diary = new Pvk($attributes);
        $errors = $diary->errors();

        if (count($errors) == 0) {
            $success = $diary->add();
            if ($success) {
                Redirect::to('/nayta_paivakirja/' . $diary->id, array('message' => 'Ajopäiväkirjamerkintä on nyt lisätty tietokantaan'));
            } else {
                Redirect::to('/uusi_paivakirja', array('error' => 'Päiväkirjamerkinnän lisääminen tietokantaan epäonnistui', 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/uusi_paivakirja', array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function nayta_muokkaussivu($diary_id) {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $diary = Pvk::find($diary_id);
        if ($user_id != $diary->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $diary_id, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $data = array();
        $data['omat_hoylat'] = Partahoyla::owned(array('id' => $user_id, 'maara' => 0));
        $data['hoylat'] = Partahoyla::all(array('maara' => 0));
        $data['terat'] = Tera::all(array('maara' => 0));
        $data['pvk'] = Pvk::find($diary_id);

        View::make('paivakirja/muokkaa_pvk.html', $data);
    }

    public static function muokkaa($diary_id) {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $diary = Pvk::find($diary_id);
        if ($user_id != $diary->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $diary_id, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $params = $_POST;
        $razor_id = $params['hoyla'];
        $blade_id = $params['tera'];

        $diary = Pvk::find($diary_id);


        if (isset($params['julkisuus']) && $params['julkisuus'] == "on") {
            $diary->julkisuus = true;
        } else {
            $diary->julkisuus = 0;
        }
        $diary->kayttaja = Kayttaja::find($user_id);
        $diary->hoyla = Partahoyla::find($razor_id);
        $diary->tera = Tera::find($blade_id);
        $diary->aggressiivisuus = $params['aggressiivisuus'];
        $diary->teravyys = $params['teravyys'];
        $diary->pehmeys = $params['pehmeys'];
        $diary->pvm = $params['pvm'];
        $diary->klo = $params['klo'];
        $diary->saippua = $params['saippua'];
        $diary->kommentit = $params['ajopvkirja'];

        $attributes = array(
            'kayttaja' => Kayttaja::find($user_id),
            'hoyla' => Partahoyla::find($razor_id),
            'tera' => Tera::find($blade_id),
            'aggressiivisuus' => $params['aggressiivisuus'],
            'teravyys' => $params['teravyys'],
            'pehmeys' => $params['pehmeys'],
            'pvm' => $params['pvm'],
            'klo' => $params['klo'],
            'saippua' => $params['saippua'],
            'kommentit' => $params['ajopvkirja'],
            'julkisuus' => $diary->julkisuus
        );


        $errors = $diary->errors();

        if (count($errors) == 0) {
            $success = $diary->update();
            if ($success) {
                Redirect::to('/nayta_paivakirja/' . $diary_id, array('message' => 'Ajopäiväkirjamerkintä on nyt päivitetty tietokantaan'));
            } else {
                Redirect::to('/muokkaa_paivakirja/' . $diary_id, array('error' => 'Päiväkirjamerkinnän muokkaaminen epäonnistui', 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/muokkaa_paivakirja/' . $diary_id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function poista($diary_id) {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $diary = Pvk::find($diary_id);
        if ($user_id != $diary->kayttaja->id) {
            Redirect::to('/nayta_paivakirja/' . $diary_id, array('error' => 'Tämä ajopäiväkirjamerkintä ei ole omasi vaan jonkun muun!'));
        }
        $date_time = $diary->pvm . " " . $diary->klo;
        $success = $diary->delete();
        if ($success) {
            Redirect::to('/listaa_omat_paivakirjat', array('success' => 'Ajopäiväkirjan merkintä ' . $date_time . ' on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/nayta_paivakirja/' . $diary_id, array('error' => 'Ajopäiväkirjamerkinnän poistaminen epäonnistui'));
        }
    }

}
