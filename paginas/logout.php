<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já existe uma sessão iniciada, caso não, inicia uma nova sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    // Faz logout do utilizador e redireciona para a página inicial
    logout() && header("Location: index.php");

    ?>
</body>

</html>