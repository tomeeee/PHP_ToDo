<?php

class KorisnikRep {

    private $db;

    function __construct() {
        $this->db = getDB();
    }

    function create(\Korisnik $korisnik) {
        $qstr = "INSERT INTO korisnik (email, lozinka,ime,prezime) VALUES (?, ?, ?, ?)";
        $stm = $this->db->prepare($qstr);
        $stm->execute([ $korisnik->email, $korisnik->lozinka, $korisnik->ime, $korisnik->prezime]);
        $korisnik->id = $this->db->lastInsertId();
    }

    function byEmail($email) {
        $qstr = "SELECT * FROM korisnik WHERE email ='" . $email . "'";
        $stm = $this->db->query($qstr);
        $obj = $stm->fetchAll(\PDO::FETCH_CLASS, "Korisnik", [$this->db]);
        if (isset($obj[0])) {
            return $obj[0];
        }
        return NULL;
    }

    function aktiviraj(\Korisnik $korisnik) {
        $qstr = "UPDATE korisnik SET status = :status WHERE id = :id";
        $stm = $this->db->prepare($qstr);
        return $stm->execute([ 'id' => $korisnik->id, 'status' => $korisnik->status]);
    }

    function zavediZadnjiLogin(\Korisnik $korisnik) {
        $qstr = "UPDATE korisnik SET zadnji_login = NOW() WHERE id = :id";
        $stm = $this->db->prepare($qstr);
        return $stm->execute([ 'id' => $korisnik->id]);
    }

}
