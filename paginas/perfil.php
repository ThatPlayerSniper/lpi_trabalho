<?php
// Inclui o ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já têm uma sessão iniciada, caso não tenha cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verifica se o utilizador tem permissão para aceder à página (admin, funcionário ou cliente)
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
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos CSS para o perfil -->
    <link rel="stylesheet" href="perfil.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
// Obtém os dados do utilizador autenticado
$utilizador  = getUser();

?>

<body>
    <div class="background">
        <div class="card">
            <div class="header">
                <!-- Mostra o ID do utilizador -->
                <h3>utilizador: <span><?= $utilizador['id_utilizador'] ?></span></h3>
                <!-- Mostra o nome do utilizador -->
                <h3>nome: <span><?= $utilizador['nome'] ?></span></h3>
                <!-- Mostra o email do utilizador -->
                <h3>Email: <?= $utilizador['endereco'] ?></h>
                    <?php
                    // Mostra o cargo se o utilizador for funcionário
                    if ($_SESSION['cargo'] == "funcionario") {
                        echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                    }
                    // Mostra o cargo se o utilizador for admin
                    if ($_SESSION['cargo'] == "admin") {
                        echo "<h3>Cargo: " . $utilizador['cargo'] . "</h3>";
                    }
                    ?>
                    <!-- Mostra o saldo atual do utilizador -->
                    <h3>Saldo: <span><?= $utilizador['saldo_atual'] ?></span></h3>
            </div>
            <!-- Botão para editar o perfil -->
            <button class="btn" onclick="window.location.href='perfilEditar.php'">Editar Perfil</button><br>
            <!-- Botão para ver as transações -->
            <button class="btn" onclick="window.location.href='transacoes.php'">Transacões</button>
            <!-- Botão para ver bilhetes ativos -->
            <button class="btn" onclick="window.location.href='bilhetes.php'">Bilhetes ativos</button>
            <button class="btn" onclick="window.location.href='bilheteshistorico.php'">historico bilhetes</button>
        </div>
    </div>
    
</body>

</html>