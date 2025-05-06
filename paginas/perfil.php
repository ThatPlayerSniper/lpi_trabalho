<?php
require_once "../basedados/basedados.h";
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
    <link rel="stylesheet" href="perfil.css">
</head>
<?php
require_once "./nav.php";
$utilizador  = getUser();

?>

<body>
    <div class="background">
        <div class="card">
            <div class="header">
                <h2><?= $utilizador['nome'] ?></h2>
                <h3>Número de cliente: <span><?= $utilizador['id_utilizador'] ?></span></h>
                    <h3>Email: <?= $utilizador['endereco'] ?></h>
                        <?php
                        if ($_SESSION['cargo'] == "funcionario") {
                            echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                        }
                        if ($_SESSION['cargo'] == "admin") {
                            echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                        } else {
                            echo "<h3>" . $utilizador['cargo'] . "</h3>";
                        }
                        ?>
            </div>
            <button class="btn" onclick="window.location.href='perfilEditar.php'">Editar Perfil</button>
        </div>
    </div>
</body>

</html>