<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';



// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <!-- Importa a fonte Sour Gummy do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Importa o ficheiro de estilos para a navegação -->
    <link rel="stylesheet" href="nav.css">
</head>
<header>
    <div class="logo">
        <a href="index.php">
            <!-- Logo do site -->
            <img src="FelixBus_V2.png" alt="Logo" class="logo-img">
        </a>
    </div>
    <nav>
        <ul>
            <?php
            // Obtém o cargo do utilizador atual
            $roleMode = VerificarCargo();
            // Mostra o cargo (provavelmente para debug)
            echo $roleMode;
            ?>
            <?php if ($roleMode == "visitante") :
                // Obtém os dados do utilizador (mesmo sendo visitante)
                $utilizador = getUser(); ?>
                <!-- Opções de navegação para visitantes -->
                <li><a href="entrar.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '') ?>">Autenticar</a></li>
                <li><a href="rota.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') ?>">Rotas</a></li>
                <li><a href="alertas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'alertas.php' ? 'active' : '') ?>">Alerta</a></li>
                <li><a href="sobre.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') ?>">Sobre</a></li>
            <?php endif; ?>

            <?php if ($roleMode == "cliente") :
                // Obtém os dados do utilizador cliente
                $utilizador = getUser(); ?>
                <!-- Mostra o saldo do cliente -->
                <label>Saldo:</label>
                <a class="turnWhite" href="saldo_gestao.php"><?= number_format($utilizador['saldo_atual'], 2) ?>€</a>
                <!-- Opções de navegação para clientes -->
                <li><a href="rota.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') ?>">Rotas</a></li>
                <li><a href="alertas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'alertas.php' ? 'active' : '') ?>">Alerta</a></li>
                <li><a href="sobre.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') ?>">Sobre</a></li>
                <li><a href="perfil.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') ?>">Perfil</a></li>
                <li><a href="logout.php" class="thebigred <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') ?>">Sair</a></li>
            <?php endif; ?>

            <?php if ($roleMode == "funcionario") :
                // Obtém os dados do utilizador funcionário
                $utilizador = getUser(); ?>
                <!-- Mostra o saldo do funcionário -->
                <label>Saldo:</label>
                <a class="turnWhite" href="saldo_gestao.php"><?= number_format($utilizador['saldo_atual'], 2) ?>€</a>
                <!-- Opções de navegação para funcionários -->
                <li><a href="rota.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') ?>">Rotas</a></li>
                <li><a href="alertas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'alertas.php' ? 'active' : '') ?>">Alerta</a></li>
                <li><a href="sobre.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') ?>">Sobre</a></li>
                <li><a href="gestao_utili.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'gestao_utili.php' ? 'active' : '') ?>">Gestão de Utilizadores</a></li>
                <li><a href="perfil.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') ?>">Perfil</a></li>
                <li><a href="logout.php" class="thebigred <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') ?>">Sair</a></li>
            <?php endif; ?>

            <?php if ($roleMode == "admin") :
                // Obtém os dados do utilizador admin
                $utilizador = getUser(); ?>
                <!-- Mostra o saldo do admin -->
                <label>Saldo:</label>
                <a class="turnWhite" href="saldo_gestao.php"><?= number_format($utilizador['saldo_atual'], 2) ?>€</a>
                <!-- Opções de navegação para administradores -->
                <li><a href="rota.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'rota.php' ? 'active' : '') ?>">Rotas</a></li>
                <li><a href="sobre.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '') ?>">Sobre</a></li>
                <li><a href="alertas.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'alertas.php' ? 'active' : '') ?>">Alerta</a></li>
                <li><a href="gestao_utili.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'gestao_utili.php' ? 'active' : '') ?>">Gestão de Utilizadores</a></li>
                <li><a href="perfil.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : '') ?>">Perfil</a></li>
                <li><a href="logout.php" class="thebigred <?= (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '') ?>">Sair</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

</html>