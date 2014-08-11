<?php

session_start();

if (empty($_SESSION['admin_usuario_logado'])) {
    //$_SESSION['pagina_acessada'] = $_SERVER['PHP_SELF'];
    header("Location: logon.php?p=" . end(explode("/", $_SERVER['PHP_SELF'])));
}