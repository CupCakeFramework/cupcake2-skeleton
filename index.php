<?php

/* Desenvolvido por Ricardo Fiorani */
//Inicialização do Framework em si
require_once('framework/inicializarFramework.php');
$_SITE = new Site();
global $_SITE;
ob_start();
$_SITE->inicializar();
ob_end_flush();