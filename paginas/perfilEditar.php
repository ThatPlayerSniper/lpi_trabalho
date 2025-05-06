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
$utilizador  = getUser();

?>

<body>
    <div class="background">
        <div class="card">
            <form action="perfilEditar.php" method="post" class="header">
                <h2><?= $utilizador['nome'] ?></h2>

                <label for="id_utilizador">nome</label>
                <input type="text" name="nome" value="<?= $utilizador['nome'] ?>">

                <br><br>

                <label for="endereco">Email:</label>
                <input type="email" name="endereco" id="endereco" value="<?= $utilizador['endereco'] ?>">

                <button type="submit" class="btn">Guardar Alterações</button>                
            </form>
            <button class="btn" onclick="window.location.href='perfil.php'">Voltar ao Perfil</button>
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $id = $_SESSION['user_id']; // Garante que tens isso definido corretamente

                if (!empty($nome) && !empty($endereco) && !empty($id)) {
                    $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco' WHERE id_utilizador = '$id'";
                    executarQuery($sql);
                    echo "<p>Perfil atualizado com sucesso!</p>";
                } elseif (!empty($nome)) {
                    $sql = "UPDATE utilizador SET nome = '$nome' WHERE id_utilizador = '$id'";
                    executarQuery($sql);
                    echo "<p>Nome atualizado com sucesso!</p>";
                } elseif (!empty($endereco)) {
                    $sql = "UPDATE utilizador SET endereco = '$endereco' WHERE id_utilizador = '$id'";
                    executarQuery($sql);
                    echo "<p>Email atualizado com sucesso!</p>";
                } else {
                    echo "<p>Todos os campos são obrigatórios.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>