<?php

class Tera extends BaseModel {

    public $id, $valmistaja, $malli, $teravyys, $pehmeys, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_manufact', 'validate_model', 'validate_sharpness', 'validate_smoothness');
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

        if ($limit == 0) {
            $query = DB::connection()->prepare('SELECT * FROM Tera ORDER BY viittauksia DESC');
            $query->execute(array());
        } else {
            $query = DB::connection()->prepare('SELECT * FROM Tera ORDER BY viittauksia DESC LIMIT :limit OFFSET :offset');
            $query->execute(array('limit' => $limit, 'offset' => $offset));
        }
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

    public function delete() {
        if ($this->viittauksia == 0) {
            $query = DB::connection()->prepare('DELETE FROM Tera WHERE id = :id');
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
        $query = DB::connection()->prepare('UPDATE Tera SET viittauksia = :viittauksia, teravyys = :teravyys, pehmeys = :pehmeys WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id, 'viittauksia' => $this->viittauksia, 'teravyys' => $this->teravyys, 'pehmeys' => $this->pehmeys));
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

    public function validate_sharpness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Terävyys', $this->teravyys));

        return $errors;
    }

    public function validate_smoothness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Pehmeys', $this->teravyys));

        return $errors;
    }

}
