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
                    echo '<div>';
                    echo '    <label>';
                    echo '        <h1>ALERTAS</h1>';
                    echo '    </label>';
                    echo '</div>';
                    while ($alerta = $resultado->fetch_assoc()) {
                        echo "<div class='note-card'>";
                        echo "<div class='note-header'>";
                        echo "Tipo de Alerta: " . $alerta['tipo_alerta'];
                        echo "</div>";
                        echo "<div class='note-body'>";
                        echo "<p>" . $alerta['descricao'] . "</p>";
                        echo "</div>";
                        echo "<div class='note-footer'>";
                        echo "<span>";
                        echo "<span>" . "Postado a " . $alerta['data_criacao'] . "</span><br>";
                        echo "<span>Termina em " . $alerta['data_expira'] . "</span>";
                        echo "</div>";
                        echo "</div>";
                        echo "<br>";
                    }
                } 
                ?>
            </div>
            <button onclick="window.location.href='alertas.php'">
                <h3>lista de alertas</h3>
            </button>
        </div>
        <div class="container">
            <div class="left-container">
            </div>
        </div>
    </div>
</body>

</html>