<?php

class TaskRep {

    private $db;

    function __construct() {
        $this->db = getDB();
    }

    function getAll(\Todo $todo, $sort = 'id', $order = 'asc') {
        $qstr = 'SELECT `task`.`id`, `task`.`naziv`, `task`.`prioritet`, `task`.`rok`, `to-do_task`.`status` ' .
                'FROM `task`' .
                'LEFT JOIN `to-do_task` ON `task`.`id` = `to-do_task`.`id_task` ' .
                'LEFT JOIN `to-do` ON `to-do_task`.`id_to-do` = `to-do`.`id` ' .
                'WHERE `to-do`.`id` = ' . $todo->id .
                ' ORDER BY ' . $sort . ' ' . $order;
        $stm = $this->db->query($qstr);
        return $stm->fetchAll(\PDO::FETCH_CLASS, "Task", [$this->db]);
    }

    function create(\Task $task, $id_todo) {
        $qstr = "INSERT INTO task (naziv, prioritet,rok) VALUES (?, ?, ?) ";
        $stm = $this->db->prepare($qstr);
        $timestamp = date('Y-m-d H:i:s', $task->rok);
        $stm->execute([$task->naziv, $task->prioritet, $timestamp]);
        $task->id = $this->db->lastInsertId();

        $qstr = "INSERT INTO `to-do_task` (`id_to-do`, `id_task` , status) VALUES (?, ?, ?) ";
        $stm = $this->db->prepare($qstr);
        $stm->execute([ $id_todo, $task->id, $task->status]);
    }

    function update(\Task $task) {
        $qstr = "UPDATE task SET naziv= :naziv, prioritet= :prioritet, rok= :rok WHERE id = :id";
        $stm = $this->db->prepare($qstr);
        $stm->execute([ 'id' => $task->id, 'naziv' => $task->naziv, 'prioritet' => $task->prioritet, 'rok' => $task->rok]);

        $qstr = "UPDATE `to-do_task` SET status= :status WHERE `id_task` = :id";
        $stm = $this->db->prepare($qstr);
        return $stm->execute([ 'id' => $task->id, 'status' => $task->status]);
    }

    function delete($id) {
        $qstr = "DELETE FROM task WHERE id = :id ";
        $stm = $this->db->prepare($qstr);
        return $stm->execute([ 'id' => $id]);
    }

    function provjeriTaskJeKorisnikov($idTask) {
        $qstr = "SELECT `korisnik_to-do`.`id_korisnik`" .
                "FROM `korisnik_to-do`" .
                "LEFT JOIN `to-do` ON `korisnik_to-do`.`id_to-do` = `to-do`.`id` " .
                "LEFT JOIN `to-do_task` ON `to-do`.`id` = `to-do_task`.`id_to-do` " .
                "LEFT JOIN `task` ON `to-do_task`.`id_task` = `task`.`id`" .
                "WHERE `to-do_task`.`id_task` = " . $idTask;
        $stm = $this->db->query($qstr);
        return $stm->fetch();
    }

}
