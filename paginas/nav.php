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
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="nav.css">
</head>
<header>
    <div class="logo">
        <a href="index.php">
            <img src="FelixBus_V2.png" alt="Logo" class="logo-img">
        </a>
    </div>
    <nav>
        <ul>

            <?php

            $roleMode = VerificarCargo();
            echo $roleMode;

            
            if ($roleMode == "visitante") {
                echo '<li><a href="entrar.php" class="' . (basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '') . '">Autenticar</a></li>';
                echo '<li><a href="rota.php" class="' . (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') . '">Rotas</a></li>';
            }
            
            if ($roleMode == "cliente") {
                $utilizador = getUser();
                echo '<label> Saldo:</label>';
                echo '<a>' . $utilizador['saldo'] . '€</a>';
                echo '<li><a href="rota.php" class="' . (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') . '">Rotas</a></li>';
                echo '<li><a href="sobre.php" class="' . (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') . '">Sobre</a></li>';
                echo '<li><a href="perfil.php" class="' . (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') . '">Perfil</a></li>';
                echo '<li><a href="logout.php" class="thebigred"' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') . '">Sair</a></li>';
            }

            if ($roleMode == "funcionario") {
                $utilizador = getUser();
                echo '<label> Saldo:</label>';
                echo '<a>' . $utilizador['saldo'] . '€</a>';
                echo '<li><a href="rota.php" class="' . (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') . '">Rotas</a></li>';
                echo '<li><a href="sobre.php" class="' . (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') . '">Sobre</a></li>';
                echo '<li><a href="perfil.php" class="' . (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') . '">Perfil</a></li>';
                echo '<li><a href="logout.php" class="thebigred"' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') . '">Sair</a></li>';
            }

            if ($roleMode == "admin") {
                $utilizador = getUser();
                echo '<label> Saldo:</label>';
                echo '<a>' . $utilizador['saldo'] . '€</a>';
                echo '<li><a href="gestao_utili.php" class="' . (basename($_SERVER['PHP_SELF']) == 'gestao_utili.php' ? 'active' : '') . '">Gestão Utilizadores</a></li>';
                echo '<li><a href="rota.php" class="' . (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') . '">Rotas</a></li>';
                echo '<li><a href="sobre.php" class="' . (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') . '">Sobre</a></li>';
                echo '<li><a href="perfil.php" class="' . (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') . '">Perfil</a></li>';
                echo '<li><a href="logout.php" class="thebigred"' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') . '">Sair</a></li>';
            }

            ?>
        </ul>
    </nav>
</header>

</html>