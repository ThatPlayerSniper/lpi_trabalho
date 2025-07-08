<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mensagem = ''; // Variável para armazenar mensagens
$sucesso = false; // Flag para controlar redirecionamento

// Processa o formulário se foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : '';

    $sql = "SELECT * FROM utilizador WHERE nome = '$nome'";
    $resultado = executarQuery($sql);

    // Verifica se o utilizador já existe
    if ($resultado->num_rows > 0) {
        $mensagem = "<h3 class='turnWhite'>ERRO: O nome de utilizador já existe</h3>";
    } else {
        // Inicializa variáveis de verificação
        $check1 = false;
        $check2 = false;

        // Verifica se todos os campos do formulário foram submetidos
        if (
            isset($_POST["nome"]) && isset($_POST["endereco"]) &&
            isset($_POST["secret"]) && isset($_POST["secret_confirm"])
        ) {
            // Verifica se as passwords coincidem
            if ($_POST["secret"] != $_POST["secret_confirm"]) {
                $check1 = false;
                $mensagem = '<label class="turnWhite">As passwords não são iguais</label>';
            } else {
                $check1 = true;
            }

            // Verifica se algum campo está vazio
            if (empty($_POST["nome"]) || empty($_POST["endereco"]) || empty($_POST["secret"]) || empty($_POST["secret_confirm"])) {
                $mensagem = '<label class="turnWhite">Existem campos por preencher</label>';
            } else {
                $check2 = true;
            }

            // Se ambas as verificações passarem, tenta registar o utilizador
            if ($check1 && $check2 == true) {
                registarUti($_POST["nome"], $_POST["endereco"], $_POST["secret"]);
                // Marca para redirecionamento após registo bem-sucedido
                $sucesso = true;
            }
        }
    }
}

// Redireciona após processamento se o registo foi bem-sucedido
if ($sucesso) {
    header("location: entrar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos CSS -->
    <link rel="stylesheet" href="registar.css">
</head>

<?php
// Inclui a barra de navegação
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Registar</h1>

                <?php
                // Mostra a mensagem se existir
                if (!empty($mensagem)) {
                    echo $mensagem;
                }
                ?>

                <!-- Formulário de registo -->
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Nome:</label>
                        <input class="input-field" type="text" name="nome" placeholder="Utilizador"><br>
                        <label class="Letras">Endereço:</label>
                        <input class="input-field" type="email" name="endereco" placeholder="Utilizador"><br>
                        <label class="Letras">Senha:</label>
                        <input class="input-field" type="password" name="secret" placeholder="Senha"><br>
                        <label class="Letras">Confirmar senha:</label>
                        <input class="input-field" type="password" name="secret_confirm" placeholder="Senha"><br>
                    </div>
                    <input class="input-submit" type="submit" value="Entrar"><br><br>
                    <a class="turnWhite" href="entrar.php">Eu tenho conta!</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>