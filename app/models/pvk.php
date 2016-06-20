<?php

class Pvk extends BaseModel {

    public $id, $pvm, $klo, $kayttaja, $hoyla, $tera, $saippua, $julkisuus, $kommentit, $aggressiivisuus, $teravyys, $pehmeys;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_soap', 'validate_aggressiveness', 'validate_sharpness', 'validate_smoothness', 'validate_comment', 'validate_date', 'validate_time');
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
                'aggressiivisuus' => $row['aggressiivisuus'],
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => $row['teravyys'],
                'pehmeys' => $row['pehmeys'],
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
                'aggressiivisuus' => $row['aggressiivisuus'],
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => $row['teravyys'],
                'pehmeys' => $row['pehmeys'],
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
                'aggressiivisuus' => $row['aggressiivisuus'],
                'tera' => Tera::find($row['tera_id']),
                'teravyys' => $row['teravyys'],
                'pehmeys' => $row['pehmeys'],
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
        $query = DB::connection()->prepare('DELETE FROM Paivakirja WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Paivakirja SET kommentit = :kommentit, partahoyla_id = :hoyla, tera_id = :tera, pvm = :pvm, saippua = :saippua, julkisuus = :julkisuus, aggressiivisuus = :aggressiivisuus, teravyys = :teravyys, pehmeys = :pehmeys WHERE id = :id');
//        $query = DB::connection()->prepare('UPDATE Paivakirja SET kommentit = :kommentit, saippua = :saippua, julkisuus = :julkisuus WHERE id = :id');
        try {
            $query->execute(array('id' => $this->id, 'kommentit' => $this->kommentit, 'hoyla' => $this->hoyla->id, 'tera' => $this->tera->id, 'pvm' => $this->pvm . ' ' . $this->klo, 'saippua' => $this->saippua, 'julkisuus' => $this->julkisuus, 'aggressiivisuus' => $this->aggressiivisuus, 'teravyys' => $this->teravyys, 'pehmeys' => $this->pehmeys));
//            $query->execute(array('id' => $this->id, 'kommentit' => $this->kommentit, 'saippua' => $this->saippua, 'julkisuus' => $this->julkisuus));
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

    public function validate_date() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Päivämäärä', $this->pvm));

        return $errors;
    }

    public function validate_sharpness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Terävyys', $this->teravyys));
        $errors = array_merge($errors, $this->validate_number_within_range('Terävyyden', $this->teravyys, 1, 5));

        return $errors;
    }

    public function validate_smoothness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Pehmeys', $this->pehmeys));
        $errors = array_merge($errors, $this->validate_number_within_range('Pehmeyden', $this->pehmeys, 1, 5));

        return $errors;
    }

    public function validate_time() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('Kellonaika', $this->klo));

        return $errors;
    }

    public function validate_aggressiveness() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_is_number('Aggressiivisuus', $this->aggressiivisuus));
        $errors = array_merge($errors, $this->validate_number_within_range('Aggressiivisuuden', $this->aggressiivisuus, 1, 5));

        return $errors;
    }

    public function validate_comment() {
        $errors = array();

        $errors = array_merge($errors, $this->validate_string_not_empty('kommentit', $this->kommentit));
        $errors = array_merge($errors, $this->validate_string_length('kommentit', $this->kommentit, 10));

        return $errors;
    }

}
