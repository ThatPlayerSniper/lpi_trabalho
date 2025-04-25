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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    logout() && header("Location: index.php");

    ?>
</body>

</html>