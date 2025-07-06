<?php

// Inclui o ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o utilizador é admin ou funcionário, caso contrário redireciona para o index
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
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos CSS -->
    <link rel="stylesheet" href="visu_perfil.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
?>

<body>
    <div class="background">
        <div class="card">
            <div class="header">
                <?php
                // Obtém o ID do utilizador a visualizar a partir do POST
                $user = (isset($_POST['vis_userID']) ? $_POST['vis_userID'] : '');

                // Escapa o ID do utilizador para evitar SQL Injection
                $user = escapeString($user);

                // Query para obter os dados do utilizador e o saldo da carteira
                $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = '$user'";
                $resultado = executarQuery($sql);

                // Verifica se encontrou resultados
                if ($resultado->num_rows > 0) {
                    // Mostra os dados do utilizador
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
                    // Mensagem caso o utilizador não seja encontrado
                    echo "<h3>O utilizador não foi encontrado!</h3>";
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>