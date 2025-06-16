<?php
// Inclusão do ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';
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
    <link rel="stylesheet" href="rota.css">
</head>
<?php
// Inclusão da barra de navegação
require_once "./nav.php";
?>


<body>
    <div><br><br>

        <?php
        // Se o utilizador for admin, mostra o botão para criar rotas
        if (seForAdminNR() == true) {
            echo "<button onclick=\"window.location.href='criaRota.php'\">Criação de Rotas</button>";
        }
        ?>
        <br><br>

        <!-- Formulário para filtrar rotas por origem e destino -->
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

                    // Obter valores do formulário, se existirem
                    $origem = isset($_POST['origem']) ? $_POST['origem'] : '';
                    $destino = isset($_POST['destino']) ? $_POST['destino'] : '';

                    // Escapar strings para evitar SQL injection
                    $origem = escapeString($origem);
                    $destino = escapeString($destino);

                    // Filtro para a query SQL conforme os campos preenchidos
                    if (!empty($origem) && !empty($destino)) {
                        $sql = "SELECT * FROM rota WHERE origem = '$origem' AND destino = '$destino'";
                    } else if (!empty($origem)) {
                        $sql = "SELECT * FROM rota WHERE origem = '$origem'";
                    } else if (!empty($destino)) {
                        $sql = "SELECT * FROM rota WHERE destino = '$destino'";
                    } else {
                        $sql = "SELECT * FROM rota";
                    }

                    // Executar a query e obter resultados
                    $result = executarQuery($sql);
                    if ($result->num_rows > 0) {
                        // Iterar sobre cada rota encontrada
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id_rota"]) ?></td>
                                <td><?= htmlspecialchars($row["origem"]) ?></td>
                                <td><?= htmlspecialchars($row["destino"]) ?></td>
                                <td><?= htmlspecialchars($row["tempo_viagem"]) ?></td>
                                <td><?= htmlspecialchars($row["distancia"]) ?> km</td>
                                <!-- Formulário para aceder às viagens da rota -->
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
                        // Caso não existam registos
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