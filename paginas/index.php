<?php

// Inclusão do ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";
// Inclusão do ficheiro de autenticação
require_once "./auth.php";
// Iniciar sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
    <link rel="stylesheet" href="index.css">
</head>
<?php
// Inclusão da barra de navegação
require_once "./nav.php";
?>

<body>
    <div class="big-box">
        <div class="container">
            <?php
            // Se o utilizador for admin, mostra botão para criar alertas
            if (seForAdminNR() == true) {
                echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
            }
            ?>
            <div class="right-container ">
                <?php
                // Query para buscar os 3 alertas mais recentes que ainda não expiraram
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
                    // Ciclo para mostrar cada alerta
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
                    <!-- Slogan do site -->
                    <h1>FelixBus - A Conectar Portugal, Viagem a Viagem!</h1>
                </div>
            </div>
        </div>
    </div>
</body>

</html>