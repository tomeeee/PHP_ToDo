<?php
require_once 'repository/todoRep.php';

class CTaskAPI {

    private $taskRep;

    function __construct($rep) {
        $this->taskRep = $rep;
        $this->provjeriPristup();
    }
    
    function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, ['naziv' => FILTER_SANITIZE_STRING,
                'prioritet' => FILTER_SANITIZE_STRING, 'status' => FILTER_SANITIZE_STRING,
                'rok' => FILTER_SANITIZE_STRING, 'id_to-do' => FILTER_SANITIZE_NUMBER_INT]);

            $jsDatum = explode('-', $post['rok']);
            $rok = mktime(0, 0, 0, $jsDatum[1], $jsDatum[2], $jsDatum[0]);

            $task = new Task();
            $task->popuni($post['naziv'], $post['prioritet'], $rok, $post['status']);
            $this->taskRep->create($task, $post['id_to-do']);

            if (isset($task->id)) {
                header('Content-Type: application/json');
                $json = array(['idTask' => $task->id, 'preostalo' => $task->preostaloDana()]);
                print(json_encode($json));
            } else {
                header('HTTP/1.1 500');
            }
        }
    }

    function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $taskID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            if ($this->taskRep->delete($taskID)) {
                echo "delete";
            } else {
                header('HTTP/1.1 500');
            }
        }
    }

    function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, ['id' => FILTER_SANITIZE_NUMBER_INT, 'naziv' => FILTER_SANITIZE_STRING,
                'prioritet' => FILTER_SANITIZE_STRING, 'status' => FILTER_SANITIZE_STRING,
                'rok' => FILTER_SANITIZE_STRING]);

            $task = new Task();
            $task->popuni($post['naziv'], $post['prioritet'], $post['rok'], $post['status']);
            $task->id = $post['id'];
            if ($this->taskRep->update($task)) { //vratit preostalo dana
                header('Content-Type: application/json');
                $json = array(['preostalo' => $task->preostaloDana()]);
                print(json_encode($json));
            } else {
                header('HTTP/1.1 500');
            }
        }
    }

    private function provjeriPristup() {
        if (!isset($_SESSION['id'])) {
            header('HTTP/1.1 401');
            exit();
        }

        $post = filter_input_array(INPUT_POST, ['id' => FILTER_SANITIZE_NUMBER_INT, 'id_to-do' => FILTER_SANITIZE_NUMBER_INT]);
        $korisnikID = $_SESSION['id'];
        if ($post['id'] !== NULL) {//update,delete
            $arr = $this->taskRep->provjeriTaskJeKorisnikov($post['id']);
            if ($korisnikID !== $arr['id_korisnik']) {
                header('HTTP/1.1 401');
                exit();
            }
        } else if ($post['id_to-do'] !== NULL) {//create
            $arr = (new TodoRep)->provjeriToDoJeKorisnikov($post['id_to-do']);
            if ($korisnikID !== $arr['id_korisnik']) {
                header('HTTP/1.1 401');
                exit();
            }
        }
    }

}
