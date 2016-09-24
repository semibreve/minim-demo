<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Minim\Authenticator;
use Minim\Configuration;

// Load up Minim.
$auth = new Authenticator(new Configuration(__DIR__ . '/../config.yml'));

// Log user out.
$auth->logout();

// Back to homepage.
header('Location: /');
die();
