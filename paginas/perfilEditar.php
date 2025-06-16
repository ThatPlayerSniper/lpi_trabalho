<?php
require_once "../basedados/basedados.h"; // Inclui o ficheiro de ligação à base de dados
require_once "./auth.php"; // Inclui o ficheiro de autenticação
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o utilizador tem permissão para aceder à página (admin, funcionário ou cliente)
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
require_once "./nav.php"; // Inclui a barra de navegação
$utilizador  = getUser(); // Vai buscar os dados do utilizador autenticado

?>

<body>
    <div class="background">
        <div class="card">
            <!-- Formulário para editar o perfil do utilizador -->
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
            <!-- Botão para voltar ao perfil -->
            <button class="btn" onclick="window.location.href='perfil.php'">Voltar ao Perfil</button>
            <?php

            // Processamento do formulário após submissão
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $pass = isset($_POST['pass']) ? escapeString($_POST['pass']) : '';
                $password = hash("sha256", $pass); // Hash da password

                $id = $_SESSION['user_id']; // Garante que tens isso definido corretamente

                // Verifica se todos os campos obrigatórios estão preenchidos
                if (!empty($nome) && !empty($endereco) && !empty($id) && !empty($pass)) {
                    $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco', secretpass = '$password' WHERE id_utilizador = '$id'";
                    executarQuery($sql); // Executa a query de atualização
                    echo "<p>Perfil atualizado com sucesso!</p>";
                }
                else {
                    echo "<p>Têm de preencher todos os campos</p>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>