<?php

$environment = require __DIR__ . '/config/environment.php';
$autoload = require __DIR__ .'/vendor/autoload.php';
$app = new \App\Controllers\SiteController($environment);
$app->inicializar();
