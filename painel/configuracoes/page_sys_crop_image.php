<?php

require_once('../includes/inc_checa_sessao.php');
require_once("../includes/inc_database.php");
require_once('../includes/inc_funcoeslib.php');
require_once('../../app/config/config.php');

if (!defined('PAINEL_BASE_URL')) {
    define('PAINEL_BASE_URL', BASE_URL);
}

$altura = $_GET['altura'];
$largura = $_GET['largura'];
