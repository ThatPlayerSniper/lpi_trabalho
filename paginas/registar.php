<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';
// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
                        echo '<label class="turnWhite">As passwords não são iguais</label>';
                        header("location: registar.php");
                    } else {
                        $check1 = true;
                    }
                    // Verifica se algum campo está vazio
                    if (empty($_POST["nome"]) || empty($_POST["endereco"]) || empty($_POST["secret"]) || empty($_POST["secret_confirm"])) {
                        echo '<label class="turnWhite">Existem campos por preecher</label>';
                        header("location: registar.php");
                    } else {
                        $check2 = true;
                    }

                    // Se ambas as verificações passarem, tenta registar o utilizador
                    if ($check1 && $check2 == true) {
                        if (registarUti($_POST["nome"], $_POST["endereco"], $_POST["secret"])) {
                            // Redireciona para a página de login após registo bem-sucedido
                            header("location: entrar.php");
                        }else{
                            // Mostra mensagem de erro se o registo falhar
                            echo '<label class="Erro</label>';
                        }
                    }
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