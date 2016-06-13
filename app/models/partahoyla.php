<?php

class Partahoyla extends BaseModel {

    public $id, $valmistaja, $malli, $aggressiivisuus, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_manufact', 'validate_model', 'validate_aggressiveness');
    }

    public static function all($options) {
        if (isset($options['sivu'])) {
            $sivu = $options['sivu'];
        } else {
            $sivu = 1;
        }
        if (isset($options['maara'])) {
            $limit = $options['maara'];
        } else {
            $limit = 10;
        }

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

    public static function owned($options) {
        if (isset($options['id'])) {
            $id = $options['id'];
        }
        if (isset($options['sivu'])) {
            $sivu = $options['sivu'];
        } else {
            $sivu = 1;
        }
        if (isset($options['maara'])) {
            $limit = $options['maara'];
        } else {
            $limit = 0;
        }

        $offset = $limit * ($sivu - 1);

        if ($limit == 0) {
            $query = DB::connection()->prepare('SELECT * FROM Partahoyla JOIN Kayttajanhoylat AS kh ON kh.partahoyla_id = partahoyla.id WHERE kh.kayttaja_id = :id ORDER BY viittauksia DESC');
            $query->execute(array('id' => $id));
        } else {
            $query = DB::connection()->prepare('SELECT * FROM Partahoyla JOIN Kayttajanhoylat AS kh ON kh.partahoyla_id = partahoyla.id WHERE kh.kayttaja_id = :id ORDER BY viittauksia DESC LIMIT :limit OFFSET :offset');
            $query->execute(array('id' => $id, 'limit' => $limit, 'offset' => $offset));
        }
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

    public function validate_manufact() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Valmistaja', $this->valmistaja));
        $errors = array_merge($errors, $this->validate_string_length('Valmistaja', $this->valmistaja, 3));

        return $errors;
    }

    public function validate_model() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Malli', $this->malli));
        $errors = array_merge($errors, $this->validate_string_length('Malli', $this->malli, 3));

        return $errors;
    }

    public function validate_aggressiveness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Aggressiivisuus', $this->aggressiivisuus));

        return $errors;
    }

}
