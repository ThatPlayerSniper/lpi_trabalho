<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="background">
        <div class="card">
            <form action="perfilEditar.php" method="post" class="header">
                <h2><?= $utilizador['nome'] ?></h2>
                <?php if (seForAdminNR()) : ?>

                    <label for="id_utilizador">nome</label>
                    <input type="text" name="nome">

                    <br><br>

                    <label for="endereco">Email:</label>
                    <input type="email" name="endereco" id="endereco">
                    <br><br>
                    <label for="endereco">Senha:</label>
                    <input type="password" name="pass" id="pass">

                    <button type="submit" class="btn">Guardar Alterações</button>
                <?php endif; ?>
            </form>
            <br>
            <form method="POST">
                <input type="hidden" name="" value="">
                <input type="number" name="number" step="0.01" placeholder="0.00" required>
                <br><br>
                <input type="submit" name="minus" value="Retirar">
                <input type="submit" name="add" value="Adicionar">
            </form>

            <button class="btn" onclick="window.location.href='perfil.php'">Voltar ao Perfil</button>
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $pass = isset($_POST['pass']) ? escapeString($_POST['pass']) : '';
                $id = $_SESSION['user_id'];

                if (!empty($nome) && !empty($endereco) && !empty($id) && !empty($pass)) {
                    $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco', secretpass = '$pass' WHERE id_utilizador = '$id'";
                    executarQuery($sql);
                    echo "<p>Perfil atualizado com sucesso!</p>";
                } else {
                    echo "<p>Têm de preencher todos os campos</p>";
                }
            }

            $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
            $minus = isset($_POST['minus']) ? $_POST['minus'] : '';
            $add = isset($_POST['add']) ? $_POST['add'] : '';

            $valor_car = 0.00;

            $sql = "SELECT * FROM utilizador WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
            $resultado = executarQuery($sql);

            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                $valor_car = floatval($row['saldo']);
            }

            if (!empty($minus)) {
                if ($valor <= $valor_car) {
                    $valorRetirar = $valor_car - $valor;
                    $sql2 = "UPDATE utilizador SET saldo = '$valorRetirar' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                    executarQuery($sql2);
                    header("Location: editar_visu.php"); // <- Substitui pelo nome correto do ficheiro
                    exit;
                } else {
                    echo "<h2>ERRO: Saldo insuficiente</h2>";
                    exit;
                }
            }

            if (!empty($add)) {
                $valorAdicionar = $valor_car + $valor;
                $sql3 = "UPDATE utilizador SET saldo = '$valorAdicionar' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                executarQuery($sql3);
                header("Location: editar_visu.php"); // <- Substitui pelo nome correto do ficheiro
                exit;
            }

            ?>
        </div>
    </div>
</body>

</html>