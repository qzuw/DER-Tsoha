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

}
