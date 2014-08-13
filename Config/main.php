<?php

$baseUrl = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
return array(
    'BASE_URL' => $baseUrl,
    'SITE_URL' => 'http://' . $_SERVER['HTTP_HOST'] . $baseUrl,
    'TITULO_SITE' => 'Projeto em CupCake2',
    'dbParams' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'cupcake2',
        'charset' => 'utf8',
    ),
);
