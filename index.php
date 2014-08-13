<?php

namespace App;

/* Desenvolvido por Ricardo Fiorani */
//Inicialização do Framework em si
$config = require dirname(__FILE__) . '/Config/main.php';
$autoload = require 'vendor/autoload.php';

$app = new \App\Controllers\SiteController($config);
ob_start();
$app->inicializar();
ob_end_flush();
