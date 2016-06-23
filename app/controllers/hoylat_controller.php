<?php

class HoylaController extends BaseController {

    public static function index($page_number) {
        $razor_amount = Partahoyla::count();
        $pagesize = 10;
        $num_pages = ceil($razor_amount / $pagesize);

        if (isset($page_number)) {
            $razors = Partahoyla::all(array('sivu' => $page_number));
        } else {
            $razors = Partahoyla::all(array());
            $page_number = 1;
        }

        $data = array('hoylat' => $razors);
        $data = array_merge($data, self::sivutus($page_number, $num_pages));

        View::make('hoyla/listaa_hoylat.html', $data);
    }

    public static function show($razor_id) {
        $user_id = null;
        if ($_SESSION && $_SESSION['tunnus']) {
            $user_id = $_SESSION['tunnus'];
            $user = Kayttaja::find($user_id);
        }

        $omistaa = false;
        //tanne $omistaa true jos kayttaja omistaa hoylan
        if ($user_id) {
            $omistaa = $user->omistaako($razor_id);
        }

        $hoyla = Partahoyla::find($razor_id);
        View::make('hoyla/nayta_hoyla.html', array('hoyla' => $hoyla, 'omistaa' => $omistaa));
    }

    public static function create_page() {
        self::check_logged_in();
        View::make('hoyla/lisaa_hoyla.html');
    }

    public static function create() {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli'],
            'aggressiivisuus' => 0
        );
        $razor = new Partahoyla($attributes);
        $errors = $razor->errors();
        if (count($errors) == 0) {
            $success = $razor->add();
            if ($success) {
                Redirect::to('/nayta_hoyla/' . $razor->id, array('message' => 'Partahöylä on nyt lisätty tietokantaan'));
            } else {
                Redirect::to('/listaa_hoylat', array('error' => 'Partahöylän lisääminen tietokantaan epäonnistui, tarkista onko se listassa jo ennestään'));
            }
        } else {
            Redirect::to('/uusi_hoyla' . $razor->id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit_page($razor_id) {
        self::check_logged_in();
        $razor = Partahoyla::find($razor_id);
        View::make('hoyla/muokkaa_hoyla.html', array('attributes' => $razor));
    }

    public static function update($razor_id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'valmistaja' => $params['valmistaja'],
            'malli' => $params['malli'],
            'aggressiivisuus' => 0
        );
        $razor = Partahoyla::find($razor_id);
        $razor->malli = $params['malli'];
        $razor->valmistaja = $params['valmistaja'];
        $errors = $razor->errors();
        if (count($errors) == 0) {
            $onnistui = $razor->update();
            if ($onnistui) {
                Redirect::to('/nayta_hoyla/' . $razor->id, array('message' => 'Partahöylän tiedot on nyt päivitetty'));
            } else {
                Redirect::to('/nayta_hoyla/' . $razor->id, array('error' => 'Partahöylän muokkaaminen epäonnistui, se saattaa olla käytössä tai omistetuksi merkitty'));
            }
        } else {
            Redirect::to('/muokkaa_hoyla' . $razor->id, array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function remove($razor_id) {
        self::check_logged_in();
        $razor = Partahoyla::find($razor_id);
        $razor_name = $razor->valmistaja . " " . $razor->malli;
        $success = $razor->delete();
        if ($success) {
            Redirect::to('/listaa_hoylat', array('success' => 'Partahöylä ' . $razor_name . ' on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/nayta_hoyla/' . $razor_id, array('error' => 'Partahöylän poistaminen epäonnistui, se on käytössä'));
        }
    }

}
