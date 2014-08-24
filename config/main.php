<?php

$baseUrl = str_replace('index.php', '', filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
return array(
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
    'template_map' => array(
        'layouts' => __DIR__ . '/../app/views/templates/',
        'views' => __DIR__ . '/../app/views/',
    ),
);
