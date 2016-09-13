<?php

require_once 'autoload.php';

use Minim\Authenticator;
use Minim\Configuration;

// Load up Minim.
$auth = new Authenticator(new Configuration(__DIR__ . '/../security.yml'));

// Log user out.
$auth->logout();

// Back to homepage.
header('Location: index.php');
die();
