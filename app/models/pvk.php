<?php

class Pvk extends BaseModel {

    public $id, $pvm, $klo, $kayttaja, $hoyla, $tera, $saippua, $julkisuus, $kommentit, $aggressiivisuus, $teravyys, $pehmeys;

    public function __construct($attributes) {
        parent::__construct($attributes);
//        $this->validators = array('validate_manufact', 'validate_model', 'validate_sharpness', 'validate_smoothness');
        $this->validators = array('validate_soap', 'validate_blade', 'validate_razor', 'validate_comment');
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

        $query = DB::connection()->prepare('SELECT * FROM Paivakirja WHERE julkisuus = true ORDER BY pvm DESC LIMIT :limit OFFSET :offset');
        $query->execute(array('limit' => $limit, 'offset' => $offset));
        $rows = $query->fetchAll();
        $pvkt = array();
        foreach ($rows as $row) {
            $aika = explode(" ", $row['pvm']);
            $pvkt[] = new Pvk(array(
                'id' => $row['id'],
                'kayttaja' => Kayttaja::find($row['kayttaja_id']),
                'hoyla' => Partahoyla::find($row['partahoyla_id']),
                'aggressiivisuus' => Tera::find($row['aggressiivisuus']),
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => Tera::find($row['teravyys']),
                'pehmeys' => Tera::find($row['pehmeys']),
                'pvm' => $aika[0],
                'klo' => $aika[1],
                'saippua' => $row['saippua'],
                'kommentit' => $row['kommentit'],
                'julkisuus' => $row['julkisuus']
            ));
        }
        return $pvkt;
    }

    public static function count() {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Paivakirja WHERE julkisuus = true');
        $query->execute();
        $row = $query->fetch();

        if ($row) {
            $maara = $row['maara'];
            return $maara;
        }
        return null;
    }

    public static function all_user($options) {
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
        
        $kayttaja = $options['kayttaja'];

        if (isset($options['julkinen'])) {
            $query = DB::connection()->prepare('SELECT * FROM Paivakirja WHERE kayttaja_id = :kayttaja AND julkisuus = TRUE ORDER BY pvm DESC LIMIT :limit OFFSET :offset');
        } else {
            $query = DB::connection()->prepare('SELECT * FROM Paivakirja WHERE kayttaja_id = :kayttaja ORDER BY pvm DESC LIMIT :limit OFFSET :offset');
        }

        $query->execute(array('limit' => $limit, 'offset' => $offset, 'kayttaja' => $kayttaja));
        $rows = $query->fetchAll();
        $pvkt = array();
        foreach ($rows as $row) {
            $aika = explode(" ", $row['pvm']);
            $pvkt[] = new Pvk(array(
                'id' => $row['id'],
                'kayttaja' => Kayttaja::find($row['kayttaja_id']),
                'hoyla' => Partahoyla::find($row['partahoyla_id']),
                'aggressiivisuus' => Tera::find($row['aggressiivisuus']),
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => Tera::find($row['teravyys']),
                'pehmeys' => Tera::find($row['pehmeys']),
                'pvm' => $aika[0],
                'klo' => $aika[1],
                'saippua' => $row['saippua'],
                'kommentit' => $row['kommentit'],
                'julkisuus' => $row['julkisuus']
            ));
        }
        return $pvkt;
    }

    public static function count_user($id) {
        $query = DB::connection()->prepare('SELECT count(*) AS maara FROM Paivakirja WHERE kayttaja_id = :kayttaja');
        $query->execute(array('kayttaja' => $id));
        $row = $query->fetch();

        if ($row) {
            $maara = $row['maara'];
            return $maara;
        }
        return null;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Paivakirja WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $aika = explode(" ", $row['pvm']);
            $pvk = new Pvk(array(
                'id' => $row['id'],
                'kayttaja' => Kayttaja::find($row['kayttaja_id']),
                'hoyla' => Partahoyla::find($row['partahoyla_id']),
                'aggressiivisuus' => Tera::find($row['aggressiivisuus']),
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => Tera::find($row['teravyys']),
                'pehmeys' => Tera::find($row['pehmeys']),
                'pvm' => $aika[0],
                'klo' => $aika[1],
                'saippua' => $row['saippua'],
                'kommentit' => $row['kommentit'],
                'julkisuus' => $row['julkisuus']
            ));
            return $pvk;
        }
        return null;
    }

    public function add() {
        $query = DB::connection()->prepare('INSERT INTO Paivakirja (kayttaja_id, partahoyla_id, aggressiivisuus, tera_id, teravyys, pehmeys, pvm, saippua, kommentit, julkisuus) VALUES (:kayttaja, :hoyla, :aggressiivisuus, :tera, :teravyys, :pehmeys, :pvm, :saippua, :kommentit, :julkisuus) RETURNING id');
        try {
            $query->execute(array('kayttaja' => $this->kayttaja->id, 'hoyla' => $this->hoyla->id, 'aggressiivisuus' => $this->aggressiivisuus, 'tera' => $this->tera->id, 'teravyys' => $this->teravyys, 'pehmeys' => $this->pehmeys, 'pvm' => $this->pvm . ' ' . $this->klo, 'saippua' => $this->saippua, 'kommentit' => $this->kommentit, 'julkisuus' => $this->julkisuus));
            $row = $query->fetch();
            $this->id = $row['id'];
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete() {
        if ($this->viittauksia == 0) {
            $query = DB::connection()->prepare('DELETE FROM Paivakirja WHERE id = :id');
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
        $query = DB::connection()->prepare('UPDATE Paivakirja SET viittauksia = :viittauksia, teravyys = :teravyys, pehmeys = :pehmeys WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id, 'viittauksia' => $this->viittauksia, 'teravyys' => $this->teravyys, 'pehmeys' => $this->pehmeys));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function validate_soap() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('saippua', $this->saippua));
        $errors = array_merge($errors, $this->validate_string_length('saippua', $this->saippua, 3));

        return $errors;
    }

    public function validate_blade() {
        $errors = array();

//        $errors = array_merge($errors, $this->validate_string_not_empty('Malli', $this->malli));
//        $errors = array_merge($errors, $this->validate_string_length('Malli', $this->malli, 3));

        return $errors;
    }

    public function validate_razor() {
        $errors = array();

//        $errors = array_merge($errors, $this->validate_string_is_number('Terävyys', $this->teravyys));

        return $errors;
    }

    public function validate_comment() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('kommentit', $this->kommentit));
        $errors = array_merge($errors, $this->validate_string_length('kommentit', $this->kommentit, 10));

        return $errors;
    }

}
