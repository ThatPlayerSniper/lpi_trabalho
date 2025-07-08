<?php
require_once '../basedados/basedados.h';

define('INCLUDE_CHECK', true);

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
    <link rel="stylesheet" href="criaRota.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Criação de Rotas</h1>
                <?php

                if (empty($_POST["origem"]) || empty($_POST["destino"]) || empty($_POST["duracao"]) || empty($_POST["distancia"])) {
                    echo '<label class="turnWhite">Existem campos por preecher</label>';
                }
                if (isset($_POST["origem"]) && isset($_POST["destino"]) && isset($_POST["duracao"]) && isset($_POST["distancia"])) {

                    $origem = $_POST["origem"] ? $_POST['origem'] : '';
                    $destino = $_POST["destino"] ? $_POST['destino'] : '';
                    $duracao = $_POST["duracao"] ? $_POST['duracao'] : '';
                    $distancia = $_POST["distancia"] ? $_POST['distancia'] : '';


                    $origem = escapeString($origem);
                    $destino = escapeString($destino);
                    $duracao = escapeString($duracao);
                    $distancia = escapeString($distancia);

                    $sql = "INSERT INTO rota(origem, destino, tempo_viagem, distancia) VALUES ('$origem', '$destino' , '$duracao', '$distancia')";
                    $resultado = executarQuery($sql);
                }
                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Origem:</label>
                        <input class="input-field" type="text" name="origem" placeholder="Castelo Branco"><br>
                        <label class="Letras">Destino:</label>
                        <input class="input-field" type="text" name="destino" placeholder="Braga"><br>
                        <label class="Letras">Duração:</label>
                        <input class="input-field" type="text" name="duracao" placeholder="24h:60m:60s"><br>
                        <label class="Letras">Distância:</label>
                        <input class="input-field" type="text" name="distancia" placeholder="150"><br>
                        <br>
                    </div>
                    <input class="input-submit" type="submit" value="Submeter"><br><br>
                    <a class="turnWhite" href="rota.php">Não quer criar mais rotas? Clique aqui!</a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</body>

</html>