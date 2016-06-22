<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['tunnus'])) {
            $kaytt_id = $_SESSION['tunnus'];
            $kayttaja = Kayttaja::find($kaytt_id);

            return $kayttaja;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['tunnus'])) {
            Redirect::to('/kirjaudu', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }

    public static function check_logged_out() {
        if (isset($_SESSION['tunnus'])) {
            Redirect::to('/', array('message' => 'Olet jo kirjautunut!'));
        }
    }

    public static function sivutus($sivu, $sivuja) {
        $data = array();

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



        return $data;
    }

}
