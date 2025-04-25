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
    <link rel="stylesheet" href="rota.css">
</head>
<?php
require_once "./nav.php";
?>


<body>
    <div><br><br>
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
                require_once "../basedados/basedados.h";
                require_once "./auth.php";

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
                        echo '<tr>';
                        echo '<td>' . ($row["id_rota"]) . '</td>';
                        echo '<td>' . ($row["origem"]) . '</td>';
                        echo '<td>' . ($row["destino"]) . '</td>';
                        echo '<td>' . ($row["tempo_viagem"]) . '</td>';
                        echo '<td>' . ($row["distancia"]) . ' km</td>';
                        echo '<td><button class="route-button">Horários</button></td>';
                        echo '</tr>';
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