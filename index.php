<?php

$config = require dirname(__FILE__) . '/Config/main.php';
$autoload = require 'vendor/autoload.php';

$app = new \App\Controllers\SiteController($config);
$app->inicializar();