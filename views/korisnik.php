<?php

class VKorisnik {

    private $header, $footer;
    public $err;

    function __construct() {
        $this->header = 'tem/header.php';
        $this->footer = 'tem/footer.php';
    }

    function login() {
        include $this->header;
        ?>

        <div class="panel panel-default login-panel" >   
            <form method="POST" >
                <div class="form-group width300px">
                    <label>E-mail:</label>
                    <input class="form-control" type="email" name="email" value="" required/>  
                </div>
                <div class="form-group width300px">
                    <label>Lozinka:</label>
                    <input class="form-control" type="password" name="lozinka" value="" required/>
                </div>
                <button class="btn btn-success" type="submit" name="login" value="Login">Login</button>
            </form>
            <?php
            $this->ispisiErr();
            ?>
            <br/>
            <a href="index.php?ctrl=korisnik&action=registracija" class="btn btn-info">Registracija</a>
        </div>
        <?php
        include $this->footer;
    }

    function registracija() {
        include $this->header;
        ?>
        <div class="panel panel-default login-panel">
            <form method="POST">
                <div class="form-group width300px">
                    <label>E-mail:</label>
                    <input class="form-control" type="email" name="email" value="" required/>  
                </div>
                <div class="form-group width300px">
                    <label>Lozinka:</label>
                    <input class="form-control" type="password" name="lozinka1" value="" required/>
                </div>
                <div class="form-group width300px">
                    <label>Lozinka:</label>
                    <input class="form-control" type="password" name="lozinka2" value="" required/>
                </div>
                <div class="form-group width300px">
                    <label>Ime:</label>
                    <input class="form-control" type="text" name="ime" value="" required/>
                </div>
                <div class="form-group width300px">
                    <label>Prezime:</label>
                    <input class="form-control" type="text" name="prezime" value="" required/>
                </div>
                <button class="btn btn-success" type="submit">Registriraj</button>
            </form>
            <?php
            $this->ispisiErr();
            ?>
        </div>  

        <?php
        include $this->footer;
    }

    private function ispisiErr() {
        if (isset($this->err)) {
            echo '<div class="alert alert-warning"><strong>' . $this->err . '</strong></div>';
        }
    }

}
