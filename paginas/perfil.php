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
$utlizador = getUser();

?>

<body>
    <div class="background">
        <div class="card">
            <div class="header">
                <h2><?= $utlizador['nome'] ?></h2>
                <p>Número de cliente: <span><?= $utlizador['id_utilizador'] ?></span></p>
                <p>Email: <?= $utlizador['endereco'] ?></p>
            </div>
            <button href="perfilEditar.php" class="btn">Editar Perfil</button>
        </div>
    </div>
</body>

</html>