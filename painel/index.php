<?php
require_once('includes/inc_checa_sessao.php');
require_once("includes/inc_database.php");
require_once('includes/inc_funcoeslib.php');
require_once('../app/config/config.php');

extract($_GET, EXTR_OVERWRITE);
extract($_POST, EXTR_OVERWRITE);

if (!defined('PAINEL_BASE_URL')) {
    define('PAINEL_BASE_URL', BASE_URL);
}

//=================================================
$sys_opc_editar = "1";
$sys_opc_ativar = "1";
$sys_opc_apagar = "1";
$sys_opc_ordem = "0";

$width = 223;
$height = 81;

$sys_this_page = "portfolio";
$sys_local = "page_" . $sys_this_page . ".php";
$sys_tabela = "tbl_" . $sys_this_page;
$sys_titulo = "Portfólio";

if ($a == "i" && $p == "s") {
    $_SESSION['mysql_id'] = inserir_banco($_POST, $sys_tabela);

    if (!empty($_SESSION['mysql_id'])) {
        $_SESSION['mysql_msg'] = "Item inserido com sucesso.";
        header("location:" . $sys_local);
        exit;
    } else {
        die("erro ao inserir");
    }
} else if ($a == "e" && $p == "s") {

    $_SESSION['mysql_id'] = alterar_banco($_POST, $sys_tabela);
    if (!empty($_SESSION['mysql_id'])) {
        $_SESSION['mysql_msg'] = "Item alterado com sucesso.";
        header("location:" . $sys_local);
        exit;
    } else {
        die("erro ao alterar");
    }
} else if ($a == "x" && !empty($item)) {
    mysql_query("delete from " . $sys_tabela . " where id='" . decode($item) . "'") or die(mysql_error());
    $_SESSION['mysql_msg'] == "Item excluído com sucesso";
    header("location:" . $sys_local);
    exit;
} else if ($a == "v" && $p == "s") {
    header("location:" . $sys_local);
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Painel</title>
        <link rel="stylesheet" type="text/css" href="<?= PAINEL_BASE_URL ?>css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?= PAINEL_BASE_URL ?>css/estilo_principal.css" />
        <?php include "includes/inc_jscripts.php"; ?>
        <script type="text/javascript" src="<?= PAINEL_BASE_URL ?>scripts/datetimepicker.js"></script>
    </head>
    <body>
        <?php include('includes/inc_menu_topo.php'); ?>

        <div id="body">
            <div class="body_tr"></div>	
            <?php include "includes/inc_header.php"; ?>
            <div id="conteudo" class="clearfix">
                <?php include "includes/inc_menu_vert.php"; ?>
                <div class="alert alert-info pull-left">
                    <p>
                        Seja bem vindo <strong><?= utf8_encode($_SESSION['admin_usuario']) ?></strong>
                    </p>
                    <p>
                        &laquo; Utilize os menus laterais para efetuar atualizações no website
                    </p>
                </div>
            </div>
            <br clear="all"/>
    </body>

</html>
