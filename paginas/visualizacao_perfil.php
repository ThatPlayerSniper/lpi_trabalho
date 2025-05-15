<?php

require_once "../basedados/basedados.h";
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (seForAdminNR() == false && seForFunNR() == false) {
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
    <link rel="stylesheet" href="visu_perfil.css">
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
                        echo "<form method='POST' action='editar_visu.php'>";
                        echo "<button type='submit' name='edit_uti' value='" . $row['id_utilizador'] . "'>editar perfil</button>";
                        echo "</form>";
                        echo "<a class='turnWhite' href='gestao_utili.php'>Voltar atrás.</a>";
                    }
                } else {
                    echo "<h3>O utilizador não foi encontrado!</h3>";
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>