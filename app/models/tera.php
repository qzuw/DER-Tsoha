<?php

class Tera extends BaseModel {

    public $id, $valmistaja, $malli, $teravyys, $pehmeys, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Tera');
	$query->execute();
	$rows = $query->fetchAll();
	$hoylat = array();
	foreach($rows as $row){
		$terat[] = new Tera(array(
			'id' => $row['id'],
			'valmistaja' => $row['valmistaja'],
			'malli' => $row['malli'],
			'teravyys' => $row['teravyys'],
			'pehmeys' => $row['pehmeys'],
			'viittauksia' => $row['viittauksia']
		));
	}
	return $terat;
    }
    
    public static function find($id){
        $query = DB::connection()->prepare('SELECT * FROM Tera WHERE id = :id');
	$query->execute(array('id' => $id));
	$row = $query->fetch();

	if($row){
		$tera = new Tera(array(
			'id' => $row['id'],
			'valmistaja' => $row['valmistaja'],
			'malli' => $row['malli'],
			'teravyys' => $row['teravyys'],
			'pehmeys' => $row['pehmeys'],
			'viittauksia' => $row['viittauksia']
		));
		return $tera;
	}
	return null;
    }
    
    public static function add(){
        $query = DB::connection()->prepare('INSERT INTO Tera (valmistaja, malli) VALUES (:valmistaja, :malli)');
	$query->execute(array('valmistaja' => $valmistaja, 'malli' => $malli));
	if (!$query->execute()){
return false;
}

return true;

    
}
