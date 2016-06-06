<?php

class Tera extends BaseModel {

    public $id, $valmistaja, $malli, $teravyys, $pehmeys, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all($options) {
        if (isset($options['sivu'])) {
            $sivu = $options['sivu'];
        } else {
            $sivu = 1;
        }
        $maara = 10;

        $limit = $maara;
        $offset = $maara * ($sivu - 1);

        $query = DB::connection()->prepare('SELECT * FROM Tera ORDER BY viittauksia LIMIT :limit OFFSET :offset');
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

    public static function count() {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Tera');
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $maara = $row['maara'];
            return $maara;
        }
        return null;
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
