<?php

class CKorisnik {

    private $korisnikRep;
    private $view;

    function __construct($rep, $view) {
        $this->korisnikRep = $rep;
        $this->view = $view;
    }

    function login() {
        $this->vecUlogiran();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, ['email' => FILTER_SANITIZE_EMAIL, 'lozinka' => FILTER_SANITIZE_STRING]);
            $korisnik = $this->korisnikRep->byEmail($post['email']);
            if ($korisnik != null && $korisnik->provjeriLozinku($post['lozinka']) && $korisnik->status === 'aktivan') {
                $_SESSION['login'] = 'true';
                $_SESSION['id'] = $korisnik->id;
                $this->zadnjiLogin($korisnik);
                header('Location: ./index.php?ctrl=todo&action=viewAll');
            } else {
                $this->view->err = "kriva sifra, email, racun nije aktiviran?";
            }
        }
        $this->view->login();
    }

    function logout() {
        //$_SESSION['login'] = 'false';
        //$_SESSION['id'] = null;
        session_unset();
        session_destroy();
        header('Location: ./index.php');
    }

    private function vecUlogiran() {
        if (isset($_SESSION['login']) && isset($_SESSION['id'])) {
            header('Location: ./index.php?ctrl=todo&action=viewAll');
        }
    }

    function registracija() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, ['email' => FILTER_SANITIZE_EMAIL,
                'lozinka1' => FILTER_SANITIZE_STRING, 'lozinka2' => FILTER_SANITIZE_STRING,
                'ime' => FILTER_SANITIZE_STRING, 'prezime' => FILTER_SANITIZE_STRING]);

            if ($post['lozinka1'] === $post['lozinka2']) {
                $user = $this->korisnikRep->byEmail($post['email']);

                if ($user != NULL) {
                    $this->view->err = "email vec postoji";
                } else {
                    $korisnik = new Korisnik();
                    $korisnik->popuni($post['email'], $post['lozinka1'], $post['ime'], $post['prezime']);
                    $this->korisnikRep->create($korisnik);
                    $this->generirajEmail($korisnik);
                    header('Location: ./index.php?ctrl=korisnik&action=login');
                }
            } else {
                $this->view->err = "Sifre nisu iste";
            }
        }

        $this->view->registracija();
    }

    private function generirajEmail(\Korisnik $korisnik) {//C:\xampp\mailoutput
        $kod = password_hash($korisnik->id . $korisnik->ime . $korisnik->prezime, PASSWORD_DEFAULT);//generirat neki random kljuc i zapisat u bazu??
        $message = 'http://localhost/L_ToDo/index.php?ctrl=korisnik&action=aktivacija&email=' . $korisnik->email . '&kod=' . $kod;
        mail($korisnik->email, 'Aktivacija', $message);
    }

    function aktivacija() {
        $get = filter_input_array(INPUT_GET, ['email' => FILTER_SANITIZE_STRING, 'kod' => FILTER_SANITIZE_STRING]);

        $korisnik = $this->korisnikRep->byEmail($get['email']);
        if ($korisnik != null) {
            if (password_verify($korisnik->id . $korisnik->ime . $korisnik->prezime, $get['kod'])) {
                $korisnik->status = 'aktivan';
                $this->korisnikRep->aktiviraj($korisnik);
                echo 'aktiviran';
            }
        } else {
            echo 'krivo';
        }
    }

    private function zadnjiLogin(\Korisnik $korisnik) {
        $this->korisnikRep->zavediZadnjiLogin($korisnik);
    }

}
