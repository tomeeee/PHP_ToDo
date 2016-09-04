<?php

class TodoRep {

    private $db;

    function __construct() {
        $this->db = getDB();
    }

    function getAll($korisnikID, $sort = 'id') {
        $qstr = "SELECT  `korisnik_to-do`.`datum_kreiranja`, `to-do`.`id`, `to-do`.`naziv`, " .
                "COUNT(`to-do_task`.`id_task`) AS broj_taskova, " .
                "SUM(if(`to-do_task`.`status`='nije zavrseno',1,0)) AS nezavrsenih " .
                "FROM `korisnik_to-do` " .
                "LEFT JOIN `to-do` ON `korisnik_to-do`.`id_to-do` = `to-do`.`id` " .
                "LEFT JOIN `to-do_task` ON `to-do`.`id` = `to-do_task`.`id_to-do` " .
                "WHERE `korisnik_to-do`.`id_korisnik` = " . $korisnikID.
                " GROUP BY `to-do`.`id` " .
                "ORDER BY " . $sort;
        $stm = $this->db->query($qstr);
        return $stm->fetchAll(\PDO::FETCH_CLASS, "Todo", [$this->db]);
    }

    function create(\Todo $todo, $korisnikID) {
        $qstr = "INSERT INTO `to-do` (naziv) VALUES (?)";
        $stm = $this->db->prepare($qstr);
        $stm->execute([ $todo->naziv]);
        $todo->id = $this->db->lastInsertId();

        $qstr = "INSERT INTO `korisnik_to-do` (`id_korisnik`, `id_to-do`, `datum_kreiranja`) VALUES (?,?, NOW())";
        $stm = $this->db->prepare($qstr);
        $stm->execute([$korisnikID, $todo->id]);
    }

    function getById($id) {
        $qstr = "SELECT `korisnik_to-do`.`datum_kreiranja`, `to-do`.`id`, `to-do`.`naziv`, " .
                "COUNT(`to-do_task`.`id_task`) AS broj_taskova, " .
                "SUM(if(`to-do_task`.`status`='nije zavrseno',1,0)) AS nezavrsenih " .
                "FROM `korisnik_to-do` " .
                "LEFT JOIN `to-do` ON `korisnik_to-do`.`id_to-do` = `to-do`.`id` " .
                "LEFT JOIN `to-do_task` ON `to-do`.`id` = `to-do_task`.`id_to-do` " .
                "WHERE `to-do`.`id` = " . $id;
        $stm = $this->db->query($qstr);
        $obj = $stm->fetchAll(\PDO::FETCH_CLASS, "Todo", [$this->db]);
        if (isset($obj[0])) {
            return $obj[0];
        }
        return NULL;
    }

    function delete($todoID) {//izbrisat sve taskove, onda todo
        $qstr = "DELETE FROM `task` " .
                "WHERE  id " .
                "IN  (SELECT `id_task` FROM `to-do_task` WHERE `id_to-do` = :id )";
        $stm = $this->db->prepare($qstr);
        $stm->execute([ 'id' => $todoID]);

        $qstr = "DELETE FROM `to-do` WHERE id = :id ";
        $stm = $this->db->prepare($qstr);
        return $stm->execute([ 'id' => $todoID]);
    }

    function provjeriToDoJeKorisnikov($idToDo) {
        $qstr = "SELECT `id_korisnik` FROM `korisnik_to-do` " .
                "WHERE `korisnik_to-do`.`id_to-do` = " . $idToDo;
        $stm = $this->db->query($qstr);
        return $stm->fetch();
    }

}
