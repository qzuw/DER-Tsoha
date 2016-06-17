<?php

class KayttajaController extends BaseController {

    public static function index($sivu) {
        $kayttajamaara = Kayttaja::count();
        $sivukoko = 10;
        $sivuja = ceil($kayttajamaara / $sivukoko);

        if (isset($sivu)) {
            $kayttajat = Kayttaja::all(array('sivu' => $sivu));
        } else {
            $kayttajat = Kayttaja::all();
            $sivu = 1;
        }

        $data = array('kayttajat' => $kayttajat);

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

        View::make('kayttaja/listaa_kayttajat.html', $data);
    }

    public static function omat_tiedot() {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $kayttaja = Kayttaja::find($id);

        $data = array('kayttaja' => $kayttaja);

        $data['hoylat'] = Partahoyla::owned(array('id' => $id));
        $data['pvk'] = Pvk::all_user(array('kayttaja' => $id, 'maara' => 5));

        View::make('kayttaja/omat_tiedot.html', $data);
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja = Kayttaja::find($id);

        $data = array('kayttaja' => $kayttaja);

        $data['pvk'] = Pvk::all_user(array('kayttaja' => $id, 'julkinen' => true));

        View::make('kayttaja/nayta_kayttaja.html', $data);
    }

    public static function lisaa_hoyla() {
        self::check_logged_in();
        $params = $_POST;
        $hid = $params['hoyla'];

        $kayttaja = Kayttaja::find($_SESSION['tunnus']);
        $hoyla = Partahoyla::find($hid);

        $lisays_ok = $kayttaja->lisaa_hoyla($hid);

        if ($lisays_ok) {
            Redirect::to('/nayta_hoyla/' . $hid, array('success' => $hoyla->valmistaja . ' ' . $hoyla->malli . ' merkittiin omistamaksesi.', 'hoyla' => $hoyla));
        } else {
            Redirect::to('/nayta_hoyla/' . $hid, array('error' => 'Tämän höylän merkitseminen omistamaksesi epäonnistui.', 'hoyla' => $hoyla));
        }
    }

    public static function poista_hoyla() {
        self::check_logged_in();
        $params = $_POST;
        $hid = $params['hoyla'];

        $kayttaja = Kayttaja::find($_SESSION['tunnus']);
        $hoyla = Partahoyla::find($hid);

        $poisto_ok = $kayttaja->poista_hoyla($hid);

        if ($poisto_ok) {
            Redirect::to('/nayta_hoyla/' . $hid, array('success' => $hoyla->valmistaja . ' ' . $hoyla->malli . ' poistettiin partahöylistäsi.', 'hoyla' => $hoyla));
        } else {
            Redirect::to('/nayta_hoyla/' . $hid, array('error' => 'Tämän höylän poistaminen omistamistasi partahöylistä epäonnistui.', 'hoyla' => $hoyla));
        }
    }

    public static function kirjaudu() {
        View::make('kayttaja/kirjaudu_rekisteroidy.html');
    }

    public static function poistolomake() {
        View::make('kayttaja/poista_tunnus.html');
    }

    public static function kirjautuminen() {
        $params = $_POST;

        $kayttaja = Kayttaja::tarkista_salasana($params['tunnus'], $params['salasana']);

        if (!$kayttaja) {
            View::make('kayttaja/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        } else {
            $_SESSION['tunnus'] = $kayttaja->id;

            Redirect::to('/', array('message' => 'Kirjauduit sisään tunnuksella ' . $kayttaja->tunnus . '!'));
        }
    }

    public static function muuta_salasana() {
        self::check_logged_in();
        $params = $_POST;

        $kayttaja1 = Kayttaja::find($_SESSION['tunnus']);
        $kayttaja = Kayttaja::tarkista_salasana($kayttaja1->tunnus, $params['salasana']);

        if (!$kayttaja) {
            Redirect::to('/omat_tiedot', array('error' => 'Virheellinen salasana!'));
        } else {
            $salt = self::generate_salt();
            $kayttaja->salasana = $params['usalasana'];
            $kayttaja->pw2 = $params['usalasana2'];
            $kayttaja->cpw = crypt(base64_encode($params['usalasana']), $salt);
            $errors = $kayttaja->validate_new_passwd();
            if (!$errors) {
                $kayttaja->update();
                Redirect::to('/omat_tiedot', array('message' => 'Salasanasi on muutettu.'));
            } else {
                Redirect::to('/omat_tiedot', array('error' => 'Virheellinen salasana!', 'errors' => $errors));
            }
        }
    }

    public static function rekisteroityminen() {
        $params = $_POST;
        $salt = self::generate_salt();
        $attributes = array(
            'tunnus' => $params['tunnus'],
            'salasana' => $params['salasana'],
            'pw2' => $params['salasana2'],
            'cpw' => crypt(base64_encode($params['salasana']), $salt)
        );

        $kayttaja = new Kayttaja($attributes);
        $errors = $kayttaja->errors();

        if (count($errors) == 0) {
            $onnistui = $kayttaja->add();
            if ($onnistui) {
                $_SESSION['tunnus'] = $kayttaja->id;
                Redirect::to('/', array('message' => 'Tunnus ' . $kayttaja->tunnus . ' on nyt lisätty tietokantaan ja kirjauduit sillä sisään.', 'kayttaja' => $kayttaja));
            } else {
                Redirect::to('/kirjaudu', array('error' => 'Tunnuksen lisääminen tietokantaan epäonnistui, tämä tunnus saattaa olla jo olemassa. ' . $attributes['cpw'], 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/kirjaudu', array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function kirjaudu_ulos() {
        self::check_logged_in();
        $_SESSION['tunnus'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function poista() {
        self::check_logged_in();
        $params = $_POST;
        $kayttaja = Kayttaja::find($_SESSION['tunnus']);
        $kayttaja2 = Kayttaja::tarkista_salasana($params['tunnus'], $params['salasana']);
        if ($kayttaja2 && $kayttaja->id == $kayttaja2->id) {
            $onnistui = $kayttaja->delete();
            if ($onnistui) {
                $_SESSION['tunnus'] = null;
                Redirect::to('/', array('success' => 'Käyttäjätunnuksesi on nyt poistettu tietokannasta'));
            } else {
                Redirect::to('/omat_tiedot', array('error' => 'Tunnuksen poistaminen epäonnistui', 'kayttaja' => $kayttaja));
            }
        } else {
            Redirect::to('/omat_tiedot', array('error' => 'Tunnuksen poistaminen epäonnistui', 'kayttaja' => $kayttaja));
        }
    }

    private static function generate_salt() {
        $salt = '$6$';
        $merkit = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        for ($i = 0; $i < 16; $i++) {
            $salt .= $merkit[mt_rand(0, strlen($merkit) - 1)];
        }
        return $salt;
    }

}
