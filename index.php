<?php

require('db/connect.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserindo dados no DB</title>
</head>
<body>
    <h1>Aula Inserindo Dados.</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Digite seu nome" required autofocus>
        <input type="email" name="email" placeholder="Digite seu email" required>
        <button type="submit" name="salve">Enviar</button>
    </form>
    <br>
    <?php
        //INSERINDO DADO NO DB DE MANEIRA SIMPLES (VÚLNERÁVEL)
        /* $sql = $pdo->prepare("INSERT INTO clientes VALUES (
            null, 
            'Willames',
            'email@algumacoisa.com', 
            '17-07-2022')");
        $sql->execute(); */

        //INSERINDO DE MODO SEGURO ANTI SQL INJECT
        if(isset($_POST['salve']) && isset($_POST['name']) && isset($_POST['email'])){

            $name = cleanDataPost($_POST['name']);
            $email = cleanDataPost($_POST['email']);
            $date = date('d-m-Y');

            //VALIDAÇÃO DE CAMPOS VAZIOS
            if($name == '' || $name == null){
                echo "<b style='color: red;'>Nome não pode ser vazio</b>";
                exit();
            }

            if($email == '' || $email == null){
                echo "<b style='color: red;'>Email não pode ser vazio</b>";
                exit();
            }

            //VALIDAÇÃO DE NOME
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                echo "<b style='color: red;'>Somente permitido letras e espaços em branco para o nome</b>";
                exit();
            }

            //VALIDAÇÃO DO EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<b style='color: red;'>Email inválido</b>";
                exit();
            }

            $sql = $pdo->prepare("INSERT INTO clientes VALUES (null, ?,?,?)");
            $sql->execute(array($name, $email, $date));

            echo "<b style='color: green;'>Cliente cadastrado com sucesso!</b>";

        }
    ?>
</body>
</html>