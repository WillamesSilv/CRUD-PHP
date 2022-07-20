<?php

require('db/connect.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Inserindo dados no DB</title>
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        th, td{
            padding: 6px;
            text-align: center;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body class="container">
    <h1 class="mt-3">Aula Inserindo Dados.</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Digite seu nome" required autofocus class="form-control form-control-sm mt-3">
        <input type="email" name="email" placeholder="Digite seu email" required class="form-control form-control-sm mt-3">
        <button type="submit" name="salve" class="btn btn-primary btn-sm mt-3">Salvar</button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="atualizarCliente" tabindex="-1" aria-labelledby="atualizarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="atualizarModalLabel">Atualizar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST">
                <input type="text" name="id-editado" id="id-editado" placeholder="ID" required autofocus class="form-control form-control-sm mt-3">
                <input type="text" name="name-editado" id="name-editado" placeholder="Digite seu nome" required autofocus class="form-control form-control-sm mt-3">
                <input type="email" name="email-editado" id="email-editado" placeholder="Digite seu email" required class="form-control form-control-sm mt-3">
                <button type="submit" id="atualizar" name="atualizar" class="btn btn-primary btn-sm mt-3">Atualizar</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Desistir</button>
        </div>
        </div>
    </div>
    </div>
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

    <?php
        
        //PROCESSO DE ATUALIZAR CLIENTE
        if(isset($_POST['atualizar']) && isset($_POST['name-editado']) && isset($_POST['email-editado']) && isset($_POST['id-editado'])){

            $id = cleanDataPost($_POST['id-editado']);
            $name = cleanDataPost($_POST['name-editado']);
            $email = cleanDataPost($_POST['email-editado']);

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

            //ATUALIZANDO CADASTRO
            $sql = $pdo->prepare("UPDATE clientes SET nome=?, email=? WHERE id=?");
            $sql->execute(array($name, $email, $id));
            
        }

    ?>

    <?php
        //SELECIONAR DADOS DA TABELA
        $sql = $pdo->prepare("SELECT * FROM clientes");
        $sql->execute();
        $datas = $sql->fetchAll();

        /* //SELECIONAR DADOS DA TABELA COM FILTRAGEM
        $sql = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $email = 'ricardo@test.com';
        $sql->execute(array($email));
        $datas = $sql->fetchAll(); */

    ?>

    <?php

        if(count($datas) > 0){

            echo "<table>
                <tr>
                    <th>CÓDIGO</th>
                    <th>NOME</th>
                    <th>EMAIL</th>
                    <th>AÇÕES</th>
                </tr>";
            
            foreach($datas as $key => $value){
                echo "<tr>
                        <td>".$value['id']."</td>
                        <td>".$value['nome']."</td>
                        <td>".$value['email']."</td>
                        <td><a href='#' id='atualizaCliente' data-bs-toggle='modal' data-id='".$value['id']."' data-nome='".$value['nome']."' data-email='".$value['email']."' data-bs-target='#atualizarCliente'>Atualizar<a/></td>
                    </tr>";
            }

            echo "</table>";
        }else {
            echo "Nenhum cliente cadastrado!";
        }

    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>

        let data = document.querySelectorAll('#atualizaCliente')

        function changeData(id, name, email){

            document.getElementById('name-editado').value = name
            document.getElementById('id-editado').value = id
            document.getElementById('email-editado').value = email

        }

        data.forEach((btn, index) => {
            
            btn.addEventListener('click', () => {

                let {id, nome, email} = btn.dataset
                
                changeData(id, nome, email)
            })
        })

        

        // console.log(data)

    </script>
</body>
</html>