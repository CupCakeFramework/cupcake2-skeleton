<?php

ob_start();

require_once('includes/inc_checa_sessao.php');
require_once('includes/inc_database.php');
require_once('includes/inc_funcoeslib.php');
require_once('../app/config/config.php');
if (!defined('PAINEL_BASE_URL')) {
    define('PAINEL_BASE_URL', BASE_URL);
}

if ($_FILES) {

    // files storage

    $dir = '../uploads/redactor/';
    if (!file_exists($dir)) {
        mkdir($dir);
    }

    $_FILES['file']['type'] = strtolower($_FILES['file']['type']);

    if (
            $_FILES['file']['type'] == 'image/png' ||
            $_FILES['file']['type'] == 'image/jpg' ||
            $_FILES['file']['type'] == 'image/gif' ||
            $_FILES['file']['type'] == 'image/jpeg' ||
            $_FILES['file']['type'] == 'image/pjpeg'
    ) {
        $fileName = md5(date('YmdHis')) . '.jpg';
        $file = $dir . $fileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $file);
        $array = array(
            'filelink' => BASE_URL . 'uploads/redactor/' . $fileName
        );

        echo stripslashes(json_encode($array));
    }
}    