<?php

$baseUrl = str_replace('index.php', '', filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
return array(
    'APP_INSTALL_SECRET' => 'a3s22d58w8q7d1v45s6a3',
    'BASE_URL' => $baseUrl,
    'SITE_URL' => 'http://' . filter_input(INPUT_SERVER, 'HTTP_HOST') . $baseUrl,
    'TITULO_SITE' => 'Projeto em CupCake2',
    'dbParams' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'cupcake2',
        'charset' => 'utf8',
    ),
    'modules' => array(
        'App',
        'CupCake2',
    ),
);
