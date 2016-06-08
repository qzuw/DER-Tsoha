<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
            $validator_errors = $this->{$validator}();
            $errors = array_merge($errors, $validator_errors);
        }

        return $errors;
    }

    public function validate_string_length($mita, $string, $length) {
        $errors = array();
        if (strlen($string) < $length) {
            $errors[] = $mita . " on liian lyhyt, pitäisi olla vähintään " . $length . " merkkiä!";
        }
        return $errors;
    }

    public function validate_string_not_empty($mita, $string) {
        $errors = array();
        if ($string == '' || $string == null) {
            $errors[] = $mita . " ei saa olla tyhjä!";
        }
        return $errors;
    }

    public function validate_string_is_number($mita, $string) {
        $errors = array();
        if (!is_numeric($string)) {
            $errors[] = $mita . " pitää olla numero!";
        }
        return $errors;
    }

    public function validate_number_within_range($mita, $num, $small, $large) {
        $errors = array();
        if (is_numeric($num)) {
            if ($num > $large || $num < $small){
                $errors[] = $mita . " pitää olla välillä " . $small . "-" . $large;
            }
        }
        return $errors;
    }

}
