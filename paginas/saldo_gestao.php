<?php
require_once "../basedados/basedados.h";
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
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
    <?php
    require_once "./nav.php";
    $utilizador  = getUser();
    ?>

<body>
    <div class="background">
        <div class="card">
            <h1>Carteira</h1>
            <div class="header">
                <h3>nome: <span><?= ($utilizador['nome']) ?></span></h3>
                <h3>saldo: <span><?= ($utilizador['saldo']) ?>€</span></h3>
                <form method="POST">
                    <input type="number" name="number" step="0.01" placeholder="0.00" required>
                    <br><br>
                    <input type="submit" name="minus" value="Retirar">
                    <input type="submit" name="add" value="Adicionar">
                </form>
                <?php
                $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
                $minus = isset($_POST['minus']) ? $_POST['minus'] : '';
                $add = isset($_POST['add']) ? $_POST['add'] : '';

                $valor_car = 0.00;

                $sql = "SELECT * FROM utilizador WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $valor_car = floatval($row['saldo']);
                }

                if (!empty($minus)) {
                    if ($valor <= $valor_car) {
                        $valorRetirar = $valor_car - $valor;
                        $sql2 = "UPDATE utilizador SET saldo = '$valorRetirar' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                        executarQuery($sql2);
                        header("Location: saldo_gestao.php"); // <- Substitui pelo nome correto do ficheiro
                        exit;
                    } else {
                        echo "<h2>ERRO: Saldo insuficiente</h2>";
                        exit;
                    }
                }

                if (!empty($add)) {
                    $valorAdicionar = $valor_car + $valor;
                    $sql3 = "UPDATE utilizador SET saldo = '$valorAdicionar' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                    executarQuery($sql3);
                    header("Location: saldo_gestao.php"); // <- Substitui pelo nome correto do ficheiro
                    exit;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>