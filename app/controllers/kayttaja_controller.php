<?php

class KayttajaController extends BaseController {

    public static function index($page) {
        $user_amount = Kayttaja::count();
        $page_size = 10;
        $num_pages = ceil($user_amount / $page_size);

        if (isset($page)) {
            $users = Kayttaja::all(array('sivu' => $page));
        } else {
            $users = Kayttaja::all();
            $page = 1;
        }

        $data = array('kayttajat' => $users);

        $data = array_merge($data, self::sivutus($page, $num_pages));

        View::make('kayttaja/listaa_kayttajat.html', $data);
    }

    public static function own_info() {
        self::check_logged_in();
        $user_id = $_SESSION['tunnus'];
        $user = Kayttaja::find($user_id);

        $data = array('kayttaja' => $user);

        $data['hoylat'] = Partahoyla::owned(array('id' => $user_id));
        $data['pvk'] = Pvk::all_user(array('kayttaja' => $user_id, 'maara' => 5));

        View::make('kayttaja/omat_tiedot.html', $data);
    }

    public static function show($user_id) {
        self::check_logged_in();
        $user = Kayttaja::find($user_id);

        $data = array('kayttaja' => $user);

        $data['pvk'] = Pvk::all_user(array('kayttaja' => $user_id, 'julkinen' => true));

        View::make('kayttaja/nayta_kayttaja.html', $data);
    }

    public static function add_owned_razor() {
        self::check_logged_in();
        $params = $_POST;
        $razor_id = $params['hoyla'];

        $user = Kayttaja::find($_SESSION['tunnus']);
        $razor = Partahoyla::find($razor_id);

        $success = $user->lisaa_hoyla($razor_id);

        if ($success) {
            Redirect::to('/nayta_hoyla/' . $razor_id, array('success' => $razor->valmistaja . ' ' . $razor->malli . ' merkittiin omistamaksesi.', 'hoyla' => $razor));
        } else {
            Redirect::to('/nayta_hoyla/' . $razor_id, array('error' => 'Tämän höylän merkitseminen omistamaksesi epäonnistui.', 'hoyla' => $razor));
        }
    }

    public static function remove_owned_razor() {
        self::check_logged_in();
        $params = $_POST;
        $razor_id = $params['hoyla'];

        $user = Kayttaja::find($_SESSION['tunnus']);
        $razor = Partahoyla::find($razor_id);

        $success = $user->poista_hoyla($razor_id);

        if ($success) {
            Redirect::to('/nayta_hoyla/' . $razor_id, array('success' => $razor->valmistaja . ' ' . $razor->malli . ' poistettiin partahöylistäsi.', 'hoyla' => $razor));
        } else {
            Redirect::to('/nayta_hoyla/' . $razor_id, array('error' => 'Tämän höylän poistaminen omistamistasi partahöylistä epäonnistui.', 'hoyla' => $razor));
        }
    }

    public static function login_page() {
        View::make('kayttaja/kirjaudu_rekisteroidy.html');
    }

    public static function delete_page() {
        View::make('kayttaja/poista_tunnus.html');
    }

    public static function log_in() {
        $params = $_POST;

        $user = Kayttaja::check_password($params['tunnus'], base64_encode($params['salasana']));

        if (!$user) {
            View::make('kayttaja/kirjaudu_rekisteroidy.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        } else {
            $_SESSION['tunnus'] = $user->id;

            Redirect::to('/', array('message' => 'Kirjauduit sisään tunnuksella ' . $user->tunnus . '!'));
        }
    }

    public static function change_passwd() {
        self::check_logged_in();
        $params = $_POST;

        $user_curr = Kayttaja::find($_SESSION['tunnus']);
        $user_upd = Kayttaja::check_password($user_curr->tunnus, base64_encode($params['salasana']));

        if (!$user_upd) {
            Redirect::to('/omat_tiedot', array('error' => 'Virheellinen salasana!'));
        } else {
            $salt = self::generate_salt();
            $user_upd->salasana = $params['usalasana'];
            $user_upd->pw2 = $params['usalasana2'];
            $user_upd->cpw = crypt(base64_encode($params['usalasana']), $salt);
            $errors = $user_upd->validate_new_passwd();
            if (!$errors) {
                $user_upd->update();
                Redirect::to('/omat_tiedot', array('message' => 'Salasanasi on muutettu.'));
            } else {
                Redirect::to('/omat_tiedot', array('error' => 'Virheellinen salasana!', 'errors' => $errors));
            }
        }
    }

    public static function registration() {
        $params = $_POST;
        $salt = self::generate_salt();
        $attributes = array(
            'tunnus' => $params['tunnus'],
            'salasana' => $params['salasana'],
            'pw2' => $params['salasana2'],
            'cpw' => crypt(base64_encode($params['salasana']), $salt)
        );

        $user = new Kayttaja($attributes);
        $errors = $user->errors();

        if (count($errors) == 0) {
            $success = $user->add();
            if ($success) {
                $_SESSION['tunnus'] = $user->id;
                Redirect::to('/', array('message' => 'Tunnus ' . $user->tunnus . ' on nyt lisätty tietokantaan ja kirjauduit sillä sisään.', 'kayttaja' => $user));
            } else {
                Redirect::to('/kirjaudu', array('error' => 'Tunnuksen lisääminen tietokantaan epäonnistui, tämä tunnus saattaa olla jo olemassa.', 'attributes' => $attributes));
            }
        } else {
            Redirect::to('/kirjaudu', array('error' => 'Tiedot eivät ole oikein', 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function log_out() {
        self::check_logged_in();
        $_SESSION['tunnus'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function delete_user() {
        self::check_logged_in();
        $params = $_POST;
        $user_curr = Kayttaja::find($_SESSION['tunnus']);
        $user_check = Kayttaja::check_password($params['tunnus'], base64_encode($params['salasana']));
        if ($user_check && $user_curr->id == $user_check->id) {
            $success = $user_curr->delete();
            if ($success) {
                $_SESSION['tunnus'] = null;
                Redirect::to('/', array('success' => 'Käyttäjätunnuksesi on nyt poistettu tietokannasta'));
            } else {
                Redirect::to('/omat_tiedot', array('error' => 'Tunnuksen poistaminen epäonnistui', 'kayttaja' => $user_curr));
            }
        } else {
            Redirect::to('/omat_tiedot', array('error' => 'Tunnuksen poistaminen epäonnistui', 'kayttaja' => $user_curr));
        }
    }

    private static function generate_salt() {
        $salt = '$6$';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        for ($i = 0; $i < 16; $i++) {
            $salt .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $salt;
    }

}
