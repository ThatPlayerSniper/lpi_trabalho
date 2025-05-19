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
    <link rel="stylesheet" href="index.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="big-box">
        <div class="container">
            <?php
            if (seForAdminNR() == true) {
                echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
            }
            ?>
            <div class="right-container ">
                <?php
                $sql = "SELECT * FROM alertas WHERE data_expira >= CURDATE() ORDER BY data_expira ASC LIMIT 3";
                $resultado = executarQuery($sql);
                if ($resultado->num_rows > 0) {
                    ?>
                    <div>
                        <label>
                            <h1>ALERTAS</h1>
                        </label>
                    </div>
                    <?php
                    while ($alerta = $resultado->fetch_assoc()) {
                        ?>
                        <div class='note-card'>
                            <div class='note-header'>
                                Tipo de Alerta: <?= htmlspecialchars($alerta['tipo_alerta']) ?>
                            </div>
                            <div class='note-body'>
                                <p><?= htmlspecialchars($alerta['descricao']) ?></p>
                            </div>
                            <div class='note-footer'>
                                <span>
                                    <span>Postado a <?= htmlspecialchars($alerta['data_criacao']) ?></span><br>
                                    <span>Termina em <?= htmlspecialchars($alerta['data_expira']) ?></span>
                                </span>
                            </div>
                        </div>
                        <br>
                        <?php
                    }
                } 
                ?>
            </div>
        </div>
        <div class="container">
            <div class="left-container">
                <div>
                    <h1>FelixBus - A Conectar Portugal, Viagem a Viagem!</h1>
                </div>
            </div>
        </div>
    </div>
</body>

</html>