<?php
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
    <link rel="stylesheet" href="index.css">
    <title>Document</title>

</head>

<?php
require_once "./nav.php";
?>

<body>
<?php
    if (seNaoAdminNR() == true) {
        echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
    }
?>
</body>

</html>