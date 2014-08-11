<?php


$host = "localhost";
$user = "root";
$pass = "";
$database = "cupcake2";

$conexao = mysql_pconnect($host, $user, $pass) or die("Erro 500 - Servidor de Banco de dados não encontrado : " . mysql_error());
mysql_select_db($database, $conexao) or die("Erro 501 - Banco não encontrado : " . mysql_error());
