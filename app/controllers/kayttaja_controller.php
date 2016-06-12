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

    public static function nayta() {
        self::check_logged_in();
        $id = $_SESSION['tunnus'];
        $kayttaja = Kayttaja::find($id);

        $data = array('kayttaja' => $kayttaja);

        $hoylat = Partahoyla::owned(array('id' => $id));

        $data['hoylat'] = $hoylat;

        View::make('kayttaja/omat_tiedot.html', $data);
    }

    public static function kirjaudu() {
        View::make('kayttaja/kirjaudu_rekisteroidy.html');
    }

    public static function kirjautuminen() {
        $params = $_POST;

        $kayttaja = Kayttaja::tarkista_salasana($params['tunnus'], $params['salasana']);

        if(!$kayttaja){
          View::make('kayttaja/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        }else{
          $_SESSION['tunnus'] = $kayttaja->id;

          Redirect::to('/', array('message' => 'Kirjauduit sisään tunnuksella ' . $kayttaja->tunnus . '!'));
        }
    }

    public static function rekisteroityminen() {
        $params = $_POST;
        $attributes = array(
            'tunnus' => $params['tunnus'],
            'salasana' => $params['salasana'],
            'pw2' => $params['salasana2']
        );

        $kayttaja = new Kayttaja($attributes);
        $errors = $kayttaja->errors();

        if (count($errors) == 0) {
            $onnistui = $kayttaja->add();
            if ($onnistui) {
                Redirect::to('/omat_tiedot', array('message' => 'Tunnus ' . $kayttaja->tunnus . ' on nyt lisätty tietokantaan', 'kayttaja' => $kayttaja));
            } else {
                Redirect::to('/kirjaudu', array('error' => 'Tunnuksen lisääminen tietokantaan epäonnistui, tämä tunnus saattaa olla jo olemassa.', 'attributes' => $attributes));
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

    public static function poista($id) {
        self::check_logged_in();
        $kayttaja = Kayttaja::find($id);
        $tunnus = $kayttaja->tunnus;
        $onnistui = $kayttaja->delete();
        if ($onnistui) {
            Redirect::to('/', array('success' => 'Käyttäjätunnuksesi on nyt poistettu tietokannasta'));
        } else {
            Redirect::to('/omat_tiedot', array('error' => 'Tunnuksen poistaminen epäonnistui', 'kayttaja' => $kayttaja));
        }
    }

}
