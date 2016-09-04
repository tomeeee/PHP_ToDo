<?php

require_once 'models/task.php';
require_once 'repository/taskRep.php';

class CTodo {

    private $todoRep;
    private $view;

    function __construct($rep, $view) {
        $this->todoRep = $rep;
        $this->view = $view;
    }

    function viewAll() {//dashboard
        $korisnikID = $_SESSION['id']; //nemoze se zaobic pristup jer nema onda ID
        $this->deleteToDo();
        $sort = filter_input(INPUT_GET, 'sortby', FILTER_SANITIZE_STRING);
        if (isset($sort)) {
            $IallTodo = new ITodo($this->todoRep->getAll($korisnikID, $sort));
        } else {
            $IallTodo = new ITodo($this->todoRep->getAll($korisnikID));
        }
        $this->view->viewAll($IallTodo);
    }

    function view() {//http://localhost/L_ToDo/index.php?ctrl=todo&action=view&id=19
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $todo = $this->todoRep->getByID($id);
        $sort = filter_input_array(INPUT_GET, ['sort' => FILTER_SANITIZE_STRING, 'order' => FILTER_SANITIZE_STRING]);
        $this->provjeriPristup($id);
        if (isset($sort['sort']) && isset($sort['order'])) {
            $todo->ITasks = new ITask((new TaskRep)->getAll($todo, $sort['sort'], $sort['order']));
        } else {
            $todo->ITasks = new ITask((new TaskRep)->getAll($todo));
        }

        if ($this->deleteToDo()) {
            header('Location: ./index.php?ctrl=todo&action=viewAll');
        }

        $this->view->view($todo);
    }

    function create() {
        $korisnikID = $_SESSION['id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, ['naziv' => FILTER_SANITIZE_STRING]);
            if (isset($post['naziv'])) {
                $todo = new Todo();
                $todo->naziv = $post['naziv'];
                $this->todoRep->create($todo, $korisnikID);
                header('Location: ./index.php?ctrl=todo&action=view&id=' . $todo->id);
            } else {
                $this->view->err = "err";
            }
        }

        $this->view->create();
    }

    private function deleteToDo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteToDo'])) {
            $todoID = filter_input(INPUT_POST, 'deleteToDo', FILTER_SANITIZE_NUMBER_INT);
            $this->provjeriPristup($todoID);
            return $this->todoRep->delete($todoID);
        }
    }

    private function provjeriPristup($todoID) {
        $korisnikID = $_SESSION['id'];
        $arr = $this->todoRep->provjeriToDoJeKorisnikov($todoID);
        if ($korisnikID !== $arr['id_korisnik']) {
            header('Location: ./index.php?ctrl=todo&action=viewAll');
        }
    }

}
