<?php
require_once "../basedados/basedados.h"; // Inclui o ficheiro de ligação à base de dados

define('INCLUDE_CHECK', true);
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (seForAdminNR() == false && seForFunNR() == false && seForClienteNR() == false) {
    header("Location: index.php");
    exit();
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
                <input type="text" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>">

                <br><br>

                <label for="endereco">Email:</label>
                <input type="email" name="endereco" id="endereco" value="<?= htmlspecialchars($utilizador['endereco']) ?>">
                <br><br>
                <label for="Senha">Senha:</label>
                <input type="password" name="pass" id="pass" value="<?= htmlspecialchars($utilizador['secretpass']) ?>">

                <button type="submit" class="btn">Guardar Alterações</button>
            </form>
            <button class="btn" onclick="window.location.href='perfil.php'">Voltar ao Perfil</button>
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $pass = isset($_POST['pass']) ? escapeString($_POST['pass']) : '';
                $password = hash("sha256", $pass);

                $id = $_SESSION['user_id'];

                // Verifica se todos os campos obrigatórios estão preenchidos
                if (!empty($nome) && !empty($endereco) && !empty($id) && !empty($pass)) {

                    // Verifica se o nome é igual ao atual
                    if ($nome == $utilizador['nome']) {
                        echo "<p>O novo nome não pode ser igual ao nome atual.</p>";
                    } else {
                        // Verifica se já existe outro utilizador com o mesmo nome
                        $sqlCheck = "SELECT id_utilizador FROM utilizador WHERE nome = '$nome' AND id_utilizador != '$id' LIMIT 1";
                        $resultCheck = executarQuery($sqlCheck);

                        if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
                            echo "<p>Já existe um utilizador com esse nome. Escolha outro nome.</p>";
                        } else {
                            $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco', secretpass = '$password' WHERE id_utilizador = '$id'";
                            executarQuery($sql);
                            echo "<p>Perfil atualizado com sucesso!</p>";
                        }
                    }
                } else {
                    echo "<p>Têm de preencher todos os campos</p>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>