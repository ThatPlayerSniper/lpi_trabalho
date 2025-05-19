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
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="rota.css">
</head>
<?php
require_once "./nav.php";
?>


<body>
    <div><br><br>

        <?php
        if (seForAdminNR() == true) {
            echo "<button onclick=\"window.location.href='criaRota.php'\">Criação de Rotas</button>";
        }
        ?>
        <br><br>

        <form method="POST">
            <input type="text" name="origem" placeholder="Origem">
            <input type="text" name="destino" placeholder="Destino">
            <input type="submit">
        </form>
    </div>
    <div class="small-box">
        <div>
        </div>
        <table class="route-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Duração</th>
                    <th>Distância</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php



                    $origem = isset($_POST['origem']) ? $_POST['origem'] : '';
                    $destino = isset($_POST['destino']) ? $_POST['destino'] : '';

                    $origem = escapeString($origem);
                    $destino = escapeString($destino);


                    //Filtro

                    if (!empty($origem) && !empty($destino)) {
                        $sql = "SELECT * FROM rota WHERE origem = '$origem' AND destino = '$destino'";
                    } else if (!empty($origem)) {
                        $sql = "SELECT * FROM rota WHERE origem = '$origem'";
                    } else if (!empty($destino)) {
                        $sql = "SELECT * FROM rota WHERE destino = '$destino'";
                    } else {
                        $sql = "SELECT * FROM rota";
                    }


                    $result = executarQuery($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id_rota"]) ?></td>
                                <td><?= htmlspecialchars($row["origem"]) ?></td>
                                <td><?= htmlspecialchars($row["destino"]) ?></td>
                                <td><?= htmlspecialchars($row["tempo_viagem"]) ?></td>
                                <td><?= htmlspecialchars($row["distancia"]) ?> km</td>
                                <form method="POST" action="viagem.php">
                                    <input type="hidden" name="rota" value="<?= htmlspecialchars($row["id_rota"]) ?>">
                                    <td>
                                        <button type="submit" class="route-button" name="rota" value="<?= htmlspecialchars($row["id_rota"]) ?>">Viagens</button>
                                    </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr>';
                        echo '<td class="NOPE">não existem registos<td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>