<?php

class Partahoyla extends BaseModel {

    public $id, $valmistaja, $malli, $aggressiivisuus, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Partahoyla');
	$query->execute();
	$rows = $query->fetchAll();
	$hoylat = array();
	foreach($rows as $row){
		$hoylat[] = new Partahoyla(array(
			'id' => $row['id'],
			'valmistaja' => $row['valmistaja'],
			'malli' => $row['malli'],
			'aggressiivisuus' => $row['aggressiivisuus'],
			'viittauksia' => $row['viittauksia']
		));
	}
	return $hoylat;
    }
    
    public static function find($id){
        $query = DB::connection()->prepare('SELECT * FROM Partahoyla WHERE id = :id');
	$query->execute(array('id' => $id));
	$row = $query->fetch();

	if($row){
		$hoyla = new Partahoyla(array(
			'id' => $row['id'],
			'valmistaja' => $row['valmistaja'],
			'malli' => $row['malli'],
			'aggressiivisuus' => $row['aggressiivisuus'],
			'viittauksia' => $row['viittauksia']
		));
		return $hoyla;
	}
	return null;
    }
    
}
