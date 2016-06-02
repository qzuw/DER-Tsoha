<?php

class Partahoyla extends BaseModel {

    public $id, $valmistaja, $malli, $aggressiivisuus, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all(){
        
    }
    
    public static function find($id){
        
    }
    
}
