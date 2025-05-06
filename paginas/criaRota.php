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

                if (isset($_POST["origem"]) && isset($_POST["destino"]) && isset($_POST["duracao"])&& isset($_POST["distancia"])) 
                {
                    if (empty($_POST["origem"]) || empty($_POST["destino"]) || empty($_POST["duracao"]) || empty($_POST["distancia"])) 
                    {
                        echo '<label class="turnWhite">Existem campos por preecher</label>';
                    }else{
                        $sql="INSERT INTO rota(origem, destino, tempo_viagem, distancia) VALUES ('$origem', '$destino' , '$duracao', '$distancia')";                    }
                }
                ?>
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Origem:</label>
                        <input class="input-field" type="text" name="origem" placeholder="Castelo Branco"><br>
                        <label class="Letras">Destino:</label>
                        <input class="input-field" type="text" name="destino" placeholder="Braga"><br>
                        <label class="Letras">Duração:</label>
                        <input class="input-field" type="text" name="duracao" placeholder="1:10"><br>
                        <label class="Letras">Distância:</label>
                        <input class="input-field" type="text" name="distancia" placeholder="150km"><br>
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

