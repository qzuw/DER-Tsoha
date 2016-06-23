<?php

class HelloController extends BaseController {

    public static function index() {

        $razors = Partahoyla::all(array('maara' => 5));
        $blades = Tera::all(array('maara' => 5));
        $diaries = Pvk::all(array('maara' => 5));

        $data = array('hoylat' => $razors, 'terat' => $blades, 'pvkt' => $diaries);

        View::make('etusivu.html', $data);
    }

}
