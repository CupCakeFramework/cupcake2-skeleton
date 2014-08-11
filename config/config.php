<?php

/*
 * 
 * Definições de URL e Diretório (Sempre terminado e iniciado em Barra / )
 * 
 */
define(BASE_URL, str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define(URL_SITE, 'http://' . $_SERVER['HTTP_HOST'] . BASE_URL);

/*
 * Definições SEO e etc
 */
define(TITULO_SITE, 'Truda');

/*
 * Ajuste de hora 
 */
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Brazil/East');
}