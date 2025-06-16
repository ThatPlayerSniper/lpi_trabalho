<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (seForAdminNR() == false && seForFunNR() == false && seForClienteNR() == false) {
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
                <h3>utilizador: <span><?= $utilizador['id_utilizador'] ?></span></h3>
                <h3>nome: <span><?= $utilizador['nome'] ?></span></h3>
                <h3>Email: <?= $utilizador['endereco'] ?></h>
                    <?php
                    if ($_SESSION['cargo'] == "funcionario") {
                        echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                    }
                    if ($_SESSION['cargo'] == "admin") {
                        echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                    }
                    ?>
                    <h3>Saldo: <span><?= $utilizador['saldo_atual'] ?></span></h3>
            </div>
            <button class="btn" onclick="window.location.href='perfilEditar.php'">Editar Perfil</button><br>
            <button class="btn" onclick="window.location.href='transacoes.php'">Transacões</button>
            <button class="btn" onclick="window.location.href='bilhetes.php'">Bilhetes ativos</button>
            <button class="btn" onclick="window.location.href='bilheteshistorico.php'">historico bilhetes</button>
        </div>
    </div>
    
</body>

</html>