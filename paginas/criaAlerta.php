<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//seNaoAdmin();
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

                if (empty($_POST["tipo"]) || empty($_POST["descricao"]) ) {
                    echo '<label class="turnWhite">Existem campos por preecher</label>';
                }
                if (isset($_POST["tipo"]) && isset($_POST["descricao"]) ) {

                    $tipo = $_POST["tipo"] ? $_POST['tipo'] : '';
                    $descricao = $_POST["descricao"] ? $_POST['descricao'] : '';

                    $tipo = escapeString($tipo);
                    $descricao = escapeString($descricao);

                    $sql = "INSERT INTO alertas(tipo_alertas, descricao) VALUES ('$tipo_alerta', '$descricao')";
                    $resultado = executarQuery($sql);
                }

                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Tipo de Alerta:</label>
                        <select name="tipo" onchange="this.form.submit()">
                            <option value="promocao">Promoção</option>
                            <option value="cancelamento">Cancelamento</option>
                            <option value="manutencao">Manutenção</option>
                            <option value="alteracao_rota">Alteração de Rota</option>
                            <option value="outro">Outro Tipo</option>
                        </select>
                        <label class="Letras">Descrição:</label>
                        <input class="input-field" type="text" name="descricao" placeholder="De que se trata..."><br>
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