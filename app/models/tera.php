<?php

class Tera extends BaseModel {

    public $id, $valmistaja, $malli, $teravyys, $pehmeys, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tera ORDER BY viittauksia');
        $query->execute();
        $rows = $query->fetchAll();
        $terat = array();
        foreach ($rows as $row) {
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

    public static function getXfrom($limit, $offset) {
        $query = DB::connection()->prepare('SELECT * FROM Tera LIMIT :limit OFFSET :offset ORDER BY viittauksia');
        $query->execute(array('limit' => $limit, 'offset' => $offset));
        $rows = $query->fetchAll();
        $terat = array();
        foreach ($rows as $row) {
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

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tera WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
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

    public function add() {
        $query = DB::connection()->prepare('INSERT INTO Tera (valmistaja, malli) VALUES (:valmistaja, :malli) RETURNING id');
        try {
            $query->execute(array('valmistaja' => $this->valmistaja, 'malli' => $this->malli));
            $row = $query->fetch();
            $this->id = $row['id'];
            $this->viittauksia = 0;
            $this->teravyys = 0;
            $this->pehmeys = 0;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
