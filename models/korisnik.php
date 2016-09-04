<?php

class Korisnik {

    public $id;
    public $email;
    public $lozinka;
    public $ime;
    public $prezime;
    public $datum_registracije;
    public $zadnji_login;
    public $status;

    function __construct() {
        
    }

    function popuni($email, $lozinka, $ime, $prezime) {
        $this->email = $email;
        $this->lozinka = password_hash($lozinka, PASSWORD_DEFAULT);
        $this->ime = $ime;
        $this->prezime = $prezime;
    }
    
    function provjeriLozinku($lozinka) {
        if (isset($this->lozinka) && password_verify($lozinka, $this->lozinka)) {
            return true;
        } else {
            return false;
        }
    }

}
