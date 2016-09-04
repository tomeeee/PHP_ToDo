<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>To-Do</title>
        <link href="js_css/css/bootstrap.min.css" rel="stylesheet">
        <link href="js_css/css/sb-admin.css" rel="stylesheet">
        <link href="js_css/css/my.css" rel="stylesheet">
        <link href="js_css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    </head>

    <body>

        <div id="wrapper">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">To-Do</a>
                </div>
                 <?php if(isset($_SESSION['login'])){ ?>
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="./index.php?ctrl=korisnik&action=logout"><i class="fa fa-fw fa-power-off"></i> Log Out </a>
                    </li>
                </ul>
                 <?php } ?>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        <li>
                            <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">