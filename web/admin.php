<?php

require_once 'init.php';

use Minim\Auth;

// This page is only accessible if logged in.
if (!Auth::isAuthenticated()) {
    header('Location: index.php');
    die();
}
