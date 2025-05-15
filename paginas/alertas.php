<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//seNaoAdmin();
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
        $sql = "SELECT * FROM alertas WHERE tipo_alerta = '$tipo' ";
    } else {
        // nada selecionado
        $sql = "SELECT * FROM alertas";
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
        if (seNaoAdminNR() == true) {
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
                $sql = "SELECT * FROM alertas WHERE tipo_alerta = '$tipo' ";
            } else {
                // nada selecionado
                $sql = "SELECT * FROM alertas";
            }
            $resultado = executarQuery($sql);
            if ($resultado->num_rows > 0) {
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
        </div>
    </div>
</body>

</html>