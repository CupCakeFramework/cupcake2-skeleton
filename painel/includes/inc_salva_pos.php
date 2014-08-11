<?php

require_once("inc_database.php");
if (isset($_GET)) {
    foreach ($_GET['items'] as $key => $value) {
        mysql_query('update ' . $_GET['tbl'] . ' set ordem = ' . $key . ' where id="' . $value . '";');
    }
    echo 'Salvo com sucesso !!';
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';
}
?>