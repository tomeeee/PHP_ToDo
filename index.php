<?php

session_start(); 
if (isset($_GET['ctrl']) && isset($_GET['action']) && (isset($_SESSION['login']) || $_GET['ctrl'] === 'korisnik' )) {
    $ctrl_name = $_GET['ctrl'];
    $action_name = $_GET['action'];
    require_once("repository/db.php");
    require_once 'controllers/' . $ctrl_name . '.php';
    $ctrl_class = 'C' . ucfirst($ctrl_name);

    if (strpos($ctrl_class, 'API') !== false) {// 'taskAPI'
        $ctrl_name = str_replace('API', '', $ctrl_name);
        require_once 'repository/' . $ctrl_name . 'Rep.php';
        require_once 'models/' . $ctrl_name . '.php';
        $rep_class = ucfirst($ctrl_name . 'Rep');
        $rep = new $rep_class();
        $ctrl = new $ctrl_class($rep);
    } else {
        require_once 'repository/' . $ctrl_name . 'Rep.php';
        require_once 'views/' . $ctrl_name . '.php';
        require_once 'models/' . $ctrl_name . '.php';
        $view_class = 'V' . ucfirst($ctrl_name);
        $rep_class = ucfirst($ctrl_name . 'Rep');
        $view = new $view_class();
        $rep = new $rep_class();
        $ctrl = new $ctrl_class($rep, $view);
    }

    $ctrl->$action_name();
} else {
    header('Location: ./index.php?ctrl=korisnik&action=login');
}
