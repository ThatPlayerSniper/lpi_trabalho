<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
seNaoAdmin();
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criaAlerta.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Criação de Alertas</h1>
                <?php

                if (empty($_POST["tipo"]) || empty($_POST["descricao"])) {
                    echo '<label class="turnWhite">Existem campos por preecher</label>';
                }
                if (isset($_POST["tipo"]) && isset($_POST["descricao"])) {

                    $tipo = $_POST["tipo"] ? $_POST['tipo'] : '';
                    $descricao = $_POST["descricao"] ? $_POST['descricao'] : '';
                    $data_fim = $_POST["data_fim"] ? $_POST['data_fim'] : '';
                    $utilizador =  $_SESSION['user_id'];

                    $tipo = escapeString($tipo);
                    $descricao = escapeString($descricao);
                    $data_fim = escapeString($data_fim);


                    $sql = "INSERT INTO alertas(id_utilizador, tipo_alerta, descricao, data_expira) VALUES ('$utilizador','$tipo', '$descricao','$data_fim')";
                    $resultado = executarQuery($sql);
                }

                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Tipo de Alerta:</label>
                        <input type="radio" name="tipo" value="promocao"> Promoção<br>
                        <input type="radio" name="tipo" value="cancelamento"> Cancelamento<br>
                        <input type="radio" name="tipo" value="manutencao"> Manutenção<br>
                        <input type="radio" name="tipo" value="alteracao_rota"> Alteração de Rota<br>
                        <input type="radio" name="tipo" value="outro"> Outro Tipo<br>
                        <label class="Letras">Descrição:</label>
                        <input class="input-field" type="text" name="descricao" placeholder="De que se trata..."><br>
                        <label class="Letras">Data de Expiração:</label>
                        <input class="input-field" type="date" name="data_fim" placeholder="2025-10-24"><br>
                        <br>
                    </div>
                    <input class="input-submit" type="submit" value="Submeter"><br><br>
                    <a class="turnWhite" href="index.php">Não quer criar mais alertas? Clique aqui!</a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</body>

</html>