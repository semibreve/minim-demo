<?php

require_once 'init.php';

use Minim\Auth;
use Minim\Security;

// This page is only accessible if logged in.
$auth = new Auth(new Security(__DIR__ . '/../security.yml'));
if (!$auth->isAuthenticated()) {
    header('Location: index.php');
    die();
}

?>