<?php
require_once '../basedados/basedados.h';

define('INCLUDE_CHECK', true);
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
    <link rel="stylesheet" href="entrar.css">
</head>

<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Entrar</h1>
                <?php

                //Se ele estiver já na condição de login ele redireciona para a página do feed
                if (Logged()) {
                    header(header: "Location: index.php");
                    exit;
                }

                if (
                    isset($_POST["nome"]) &&
                    isset($_POST["secret"])
                ) {
                    if (empty($_POST["nome"]) || empty($_POST["secret"])) {
                        echo '<label class="turnWhite">Existem campos por preecher</label>';
                    } else {
                        if (login($_POST["nome"], $_POST["secret"])) {
                            header(header: "Location: index.php"); //Se sucesso entra no site
                            exit;
                        } else {
                            echo '<label class="turnWhite">Erro ao entrar</label>';
                        }
                    }
                }
                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Nome:</label>
                        <input class="input-field" type="text" name="nome" placeholder="Utilizador"><br>
                        <label class="Letras">Senha:</label>
                        <input class="input-field" type="password" name="secret" placeholder="Password"><br>
                        <br>
                    </div>
                    <input class="input-submit" type="submit" value="Entrar"><br><br>
                    <a class="turnWhite" href="registar.php">Não têm conta? Registe-se!</a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</body>

</html>