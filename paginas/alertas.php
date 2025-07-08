<?php
require_once '../basedados/basedados.h';

define('INCLUDE_CHECK', true);

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
    <link rel="stylesheet" href="alertas.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <?php
    //Vai buscar os dados do form (trenario)
    $tipo = isset($_POST['tipo_alerta']) ? $_POST['tipo_alerta'] : '';

    //Verificão para o filtro
    if (!empty($tipo)) {
        // filtro selecionado
        $tipo = escapeString($tipo);
        $sql = "SELECT * FROM alertas WHERE tipo_alerta = '$tipo' ORDER BY data_expira ASC ";
    } else {
        // nada selecionado
        $sql = "SELECT * FROM alertas ORDER BY data_expira ASC";
    }
    $resultado = executarQuery($sql);

    ?>
    <div class="big-box">
        <div>
            <div>
                <div>
                    <label>
                        <h1>Alertas</h1>
                    </label>
                </div>
            </div>
            <form method="POST">
                <div">
                    <label>Tipo:</label>
                    <select name="tipo_alerta">
                        <option value="">-- Seleciona um --</option>
                        <option value="alteracao_rota">Alteração de Rotas</option>
                        <option value="cancelamento">Cancelamento</option>
                        <option value="manutencao">Manutenção</option>
                        <option value="promocao">Promoção</option>
                        <option value="outro">Outro</option>
                    </select>
                    <button type="submit">Pesquisar</button>
        </div>
        </form>
    </div>
    <div class="container">
        <?php
        if (seForAdminNR() == true) {
            echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
        }
        ?>
        <div class="right-container ">
            <?php

            //Vai buscar os dados do form (trenario)
            $tipo = isset($_POST['tipo_alerta']) ? $_POST['tipo_alerta'] : '';

            //Verificão para o filtro
            if (!empty($tipo)) {
                // filtro selecionado
                $tipo = escapeString($tipo);
                $sql = "SELECT * FROM alertas WHERE tipo_alerta = '$tipo' ORDER BY data_expira ASC";
            } else {
                // nada selecionado
                $sql = "SELECT * FROM alertas ORDER BY data_expira ASC";
            }
            $resultado = executarQuery($sql);
            if ($resultado->num_rows > 0) {
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
                        <?php
                            if (seForAdminNR() == true) {
                         ?>
                        <form method="GET" action="editaAlerta.php">
                        <input type="hidden" name="id_alerta" value="<?= htmlspecialchars($alerta['id_alerta']) ?>">
                        <button type="submit" class="route-button">Editar Alerta</button>
                        </form>
                        <?php } ?>
                    </div>
                    <br>
            <?php
                }
            }
            ?>
        </div>
    </div>
    </div>
</body>

</html>