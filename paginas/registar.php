<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
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
    <link rel="stylesheet" href="registar.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Registar</h1>
                <?php

                $check1 = false;
                $check2 = false;

                $nome=($_POST["nome"]);
                $endereco=($_POST["endereco"]);
                $secret=($_POST["secret"]);
                $secret_confirm=($_POST["secret_confirm"]);

                if (
                    isset($_POST["nome"]) && isset($_POST["endereco"]) &&
                    isset($_POST["secret"]) && isset($_POST["secret_confirm"])
                ) {
                    if ($_POST["secret"] != $_POST["secret_confirm"]) {
                        $check1 = false;
                        echo '<label class="turnWhite">As passwords não são iguais</label>';
                        header("location: registar.php");
                    } else {
                        $check1 = true;
                    }
                    if (empty($_POST["nome"]) || empty($_POST["endereco"]) || empty($_POST["secret"]) || empty($_POST["secret_confirm"])) {
                        echo '<label class="turnWhite">Existem campos por preecher</label>';
                        header("location: registar.php");
                    } else {
                        $check2 = true;
                    }

                    if ($check1 && $check2 == true) {
                        if (registarUti($_POST["nome"], $_POST["endereco"], $_POST["secret"])) {
                            header("location: login.php");
                        }else{
                            echo '<label class="Erro</label>';
                        }
                    }
                }

                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Nome:</label>
                        <input class="input-field" type="text" name="nome" placeholder="Utilizador"><br>
                        <label class="Letras">Endereço:</label>
                        <input class="input-field" type="text" name="endereco" placeholder="Utilizador"><br>
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