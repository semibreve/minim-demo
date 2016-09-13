<?php

require_once 'autoload.php';

use Minim\Authenticator;
use Minim\Request;
use Minim\Configuration;

// Did user attempt login?
$attempted = Request::isLoginFormSubmitted();

// Try to log user in.
$auth = new Authenticator(new Configuration(__DIR__ . '/../security.yml'));
if ($auth->isAuthenticated() || // Already logged in?
    ($attempted && $auth->authenticate(Request::getLoginEmail(), Request::getLoginPassword()))) {
    header('Location: /admin.php');
    die();
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="/bower_components/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/bower_components/bootswatch/simplex/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/minim.css"/>
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <title>Log in - Minim</title>
</head>
<body>
<div class="center-body">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="/svg/logo.svg" class="logo">
        </div>
    </div>
    <?php if ($attempted) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                Invalid username or password.
            </div>
        </div>
    </div>
    <?php } ?>
    <form class="form-signin" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="inputEmail">Email address</label>
                    <input type="email" id="inputEmail" class="form-control input-lg" name="email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" id="inputPassword" class="form-control input-lg" name="password" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-lg btn-info btn-block margin-bottom-plus" href="/">Cancel</a>
            </div>
            <div class="col-md-6">
                <button class="btn btn-lg btn-success btn-block margin-bottom-plus" type="submit">Sign in</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12 text-muted text-center small copyright">
            Minim | Copyright &copy; Saul Johnson
        </div>
    </div>
</div>
</body>
</html>
