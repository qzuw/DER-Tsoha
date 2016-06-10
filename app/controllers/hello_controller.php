<?php

class HelloController extends BaseController {

    public static function index() {

        $hoylat = Partahoyla::all(array('maara' => 5));
        $terat = Tera::all(array('maara' => 5));

        $data = array('hoylat' => $hoylat, 'terat' => $terat,);

        View::make('etusivu.html', $data);
    }

}
