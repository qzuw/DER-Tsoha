<?php

class Partahoyla extends BaseModel {

    public $id, $valmistaja, $malli, $aggressiivisuus, $viittauksia;

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
        $offset = $limit * ($sivu - 1);

        $query = DB::connection()->prepare('SELECT * FROM Partahoyla ORDER BY viittauksia DESC LIMIT :limit OFFSET :offset');
        $query->execute(array('limit' => $limit, 'offset' => $offset));
        $rows = $query->fetchAll();
        $hoylat = array();
        foreach ($rows as $row) {
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

    public static function count() {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Partahoyla');
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $maara = $row['maara'];
            return $maara;
        }
        return null;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Partahoyla WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
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

    public function add() {
        $query = DB::connection()->prepare('INSERT INTO Partahoyla (valmistaja, malli) VALUES (:valmistaja, :malli) RETURNING id');
        try {
            $query->execute(array('valmistaja' => $this->valmistaja, 'malli' => $this->malli));
            $row = $query->fetch();
            $this->id = $row['id'];
            $this->viittauksia = 0;
            $this->aggressiivisuus = 0;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete() {
        if ($this->viittauksia == 0) {
            $query = DB::connection()->prepare('DELETE FROM Partahoyla WHERE id = :id');
            try {
                $query->execute(array('id' => $this->id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Partahoyla SET viittauksia = :viittauksia, aggressiivisuus = :aggressiivisuus WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id, 'viittauksia' => $this->viittauksia, 'aggressiivisuus' => $this->aggressiivisuus));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
