<?php

class Kayttaja extends BaseModel {

    public $id, $tunnus, $salasana, $pw2, $cpw;

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

    public static function omistaako($hid) {
        $kid = $_SESSION['tunnus'];
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Kayttajanhoylat WHERE kayttaja_id = :kid AND partahoyla_id = :hid');
        $query->execute(array('hid' => $hid, 'kid' => $kid));
        $row = $query->fetch();

        if ($row) {
            if ($row['maara'] > 0) {
                return true;
            }
        }
        return false;
    }

    public static function lisaa_hoyla($hid) {
        $kid = $_SESSION['tunnus'];
        $query = DB::connection()->prepare('INSERT INTO Kayttajanhoylat (kayttaja_id, partahoyla_id) VALUES (:kid, :hid)');
        try {
            $query->execute(array('hid' => $hid, 'kid' => $kid));
            $row = $query->fetch();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function poista_hoyla($hid) {
        $kid = $_SESSION['tunnus'];
        $query = DB::connection()->prepare('DELETE FROM Kayttajanhoylat WHERE (kayttaja_id, partahoyla_id) = (:kid, :hid)');
        try {
            $query->execute(array('hid' => $hid, 'kid' => $kid));
            return true;
        } catch (Exception $e) {
            return false;
        }
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
            $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->cpw));
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
            $query->execute(array('id' => $this->id, 'salasana' => $this->cpw));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function tarkista_salasana($tunnus, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus');
        $query->execute(array('tunnus' => $tunnus));
        $row = $query->fetch();

        if ($row) {
            // myohemmin if (crypt($user_input, $digest) == $digest)
            if ($row['salasana'] == crypt($salasana, $row['salasana'])) {
                $kayttaja = new Kayttaja(array(
                    'id' => $row['id'],
                    'tunnus' => $row['tunnus'],
                    'cpw' => $row['salasana']
                ));
                return $kayttaja;
            }
        }
        return null;
    }

    public function validate_new_passwd() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Salasana', $this->salasana));
        $errors = array_merge($errors, $this->validate_string_length('Salasana', $this->salasana, 10));

        if ($this->salasana != $this->pw2) {
            $errors = array_merge($errors, array("Annetut salasanat eivät ole samat!"));
        }

        return $errors;
    }

    public function validate_username() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Tunnus', $this->tunnus));
        $errors = array_merge($errors, $this->validate_string_length('Tunnus', $this->tunnus, 3));

        return $errors;
    }

}
