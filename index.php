<?php
namespace frontend;
/* Desenvolvido por Ricardo Fiorani */
//Inicialização do Framework em si
require 'vendor/autoload.php';
require 'app/controllers/siteController.php';
$_SITE = new Site();
global $_SITE;
ob_start();
$_SITE->inicializar();
ob_end_flush();