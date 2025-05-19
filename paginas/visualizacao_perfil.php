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
                $user = (isset($_POST['vis_userID']) ? $_POST['vis_userID'] : '');

                $user = escapeString($user);

                $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = '$user'";
                $resultado = executarQuery($sql);


                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                ?>
                        <h3>utilizador: <?php echo htmlspecialchars($row['id_utilizador']); ?></h3>
                        <h3>nome: <?php echo htmlspecialchars($row['nome']); ?></h3>
                        <h3>endereco: <?php echo htmlspecialchars($row['endereco']); ?></h3>
                        <h3>Cargo: <?php echo htmlspecialchars($row['cargo']); ?></h3>
                        <h3>Saldo: <?php echo htmlspecialchars($row['saldo_atual']); ?></h3>
                        <a class="turnWhite" href="gestao_utili.php">Voltar atrás.</a>
                <?php
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