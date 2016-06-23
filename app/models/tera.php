<?php

class Tera extends BaseModel {

    public $id, $valmistaja, $malli, $teravyys, $pehmeys, $viittauksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_manufact', 'validate_model', 'validate_sharpness', 'validate_smoothness');
    }

    public static function all($options) {
        $page = self::page_from_options($options);
        $limit = self::limit_from_options($options);
        $offset = $limit * ($page - 1);

        if ($limit == 0) {
            $query = DB::connection()->prepare('SELECT * FROM Teranakyma ORDER BY viittauksia DESC');
            $query->execute(array());
        } else {
            $query = DB::connection()->prepare('SELECT * FROM Teranakyma ORDER BY viittauksia DESC LIMIT :limit OFFSET :offset');
            $query->execute(array('limit' => $limit, 'offset' => $offset));
        }
        $rows = $query->fetchAll();
        $blades = array();
        foreach ($rows as $row) {
            $blades[] = new Tera(array(
                'id' => $row['id'],
                'valmistaja' => $row['valmistaja'],
                'malli' => $row['malli'],
                'teravyys' => $row['teravyys'],
                'pehmeys' => $row['pehmeys'],
                'viittauksia' => $row['viittauksia']
            ));
        }
        return $blades;
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Teranakyma');
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $amount = $row['maara'];
            return $amount;
        }
        return null;
    }

    public static function find($blade_id) {
        try {
            $query = DB::connection()->prepare('SELECT * FROM Teranakyma WHERE id = :id');
            $query->execute(array('id' => $blade_id));
            $row = $query->fetch();
        } catch (Exception $e) {
            return null;
        }

        if ($row) {
            $blade = new Tera(array(
                'id' => $row['id'],
                'valmistaja' => $row['valmistaja'],
                'malli' => $row['malli'],
                'teravyys' => $row['teravyys'],
                'pehmeys' => $row['pehmeys'],
                'viittauksia' => $row['viittauksia']
            ));
            return $blade;
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
        if ($this->viittauksia == 0) {
            $query = DB::connection()->prepare('UPDATE Tera SET valmistaja = :valmistaja, malli = :malli WHERE id = :id');
            try {
                $query->execute(array('id' => $this->id, 'valmistaja' => $this->valmistaja, 'malli' => $this->malli));
                return true;
            } catch (Exception $e) {
                return false;
            }
        } else {
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
