<?php

// CONFIGURAÇÕES GERAIS
$server = "localhost";
$user = "root";
$password = "";
$db = "first_db";

//CONEXÃO
try{
    $pdo = new PDO("mysql:host=$server;dbname=$db",$user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $erro){
    echo "Falha ao tentar se conectar com o banco de dados!";
}

//FUNCÇÃO PARA SANITIZAR DADOS (LIMPAR DADOS)
function cleanDataPost($data){

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
    
}

?>