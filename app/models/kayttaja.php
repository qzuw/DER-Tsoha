<?php

class Kayttaja extends BaseModel {

    public $id, $tunnus, $salasana, $pw2;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_new_passwd');
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

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja ORDER BY tunnus LIMIT :limit OFFSET :offset');
        $query->execute(array('limit' => $limit, 'offset' => $offset));
        $rows = $query->fetchAll();
        $kayttajat = array();
        foreach ($rows as $row) {
            $kayttajat[] = new Kayttaja(array(
                'id' => $row['id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));
        }
        return $kayttajat;
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Kayttaja');
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $maara = $row['maara'];
            return $maara;
        }
        return null;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kayttaja = new Kayttaja(array(
                'id' => $row['id'],
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana']
            ));
            return $kayttaja;
        }
        return null;
    }

    public function add() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (tunnus, salasana) VALUES (:tunnus, :salasana) RETURNING id');
        try {
            $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana));
            $row = $query->fetch();
            $this->id = $row['id'];
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Kayttaja WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET salasana = :salasana WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id, 'salasana' => $this->salasana));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function validate_new_passwd() {
        $errors = array();

        $errors[] = $this->validate_string_not_empty('Salasana', $this->salasana);
        $errors[] = $this->validate_string_length('Salasana', $this->salasana, 10);

        if ($this->salasana != $this->pw2) {
            $errors[] = "Annetut salasanat eivät ole samat!";
        }

        return $errors;
    }

    public function validate_username() {
        $errors = array();

        $errors[] = $this->validate_string_not_empty('Tunnus', $this->tunnus);
        $errors[] = $this->validate_string_length('Tunnus', $this->tunnus, 3);

        return $errors;
    }

}
