<?php

// CONFIGURAÇÕES GERAIS
$server = "localhost";
$user = "root";
$password = "";
$db = "first_db";

//CONEXÃO
$pdo = new PDO("mysql:host=$server;dbname=$db",$user, $password);

//FUNCÇÃO PARA SANITIZAR DADOS (LIMPAR DADOS)
function cleanDataPost($data){

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
    
}

?>