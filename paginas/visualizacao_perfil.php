<?php

require_once "../basedados/basedados.h";
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
    <link rel="stylesheet" href="visu_perfil.css" ;
        </head>
    <?php
    require_once "./nav.php";
    ?>

<body>
    <div class="background">
        <div class="card">
            <div class="header">
                <?php
                $utilizador = (isset($_POST['vis_userID']) ? $_POST['vis_userID'] : '');

                if ($utilizador == "") {
                    header("Location: index.php");
                    exit;
                }

                $utilizador = escapeString($utilizador);

                $sql = "SELECT * FROM utilizador WHERE id_utilizador = '$utilizador'";
                $resultado = executarQuery($sql);


                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<h3>utilizador: " . $row['id_utilizador'] . "</h3>";
                        echo "<h3>nome: " . $row['nome'] . "</h3>";
                        echo "<h3>endereco: " . $row['endereco'] . " </h3>";
                        echo "<h3>secretpass: " . $row['secretpass'] . "</h3>";
                        echo "<h3>Cargo: " . $row['cargo'] . "</h3>";
                        echo "<h3>Saldo: " . $row['saldo'] . "</h3>";
                        //if()
                    }
                } else {
                    echo "<h3>O utilizador não foi encontrado!</h3>";
                }
                ?>
                <a class="turnWhite" href="gestao_utili.php">voltar atrás.</a>
            </div>
        </div>
    </div>
</body>

</html>