<?php
require_once '../basedados/basedados.h';
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
    <link rel="stylesheet" href="alertas.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="big-box">
        <div>
            <div>
                <h1>Viagens</h1>
                <?php
                $utilizador  = getUser();

                $id_rota = isset($_POST['rota']) ? $_POST['rota'] : '';

                $sql = "SELECT * FROM rota WHERE id_rota = '$id_rota'";
                $result = executarQuery($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <h2 style="margin: 0;"><?= htmlspecialchars($row["origem"]) ?></h2>
                            <h2 style="margin: 0;">--></h2>
                            <h2 style="margin: 0;"><?= htmlspecialchars($row["destino"]) ?></h2>
                        </div>
                        <h2>Viagens disponíveis:</h2>
                        <?php
                        $sql = "SELECT * FROM viagem WHERE id_rota = '$id_rota'";
                        $result = executarQuery($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row2 = $result->fetch_assoc()) {

                        ?>
                            <div class="note-card">
                                    <div class="note-header" style="display: flex; align-items: center; gap: 10px;">
                                <h3>Data <?= htmlspecialchars($row2["data_viagem"]) ?></h3>
                            </div>
                            <div class="note-body">
                                <h3>Hora da Partida: <?= htmlspecialchars($row2["hora_partida"]) ?></h3>
                                <h3>Hora da Chegada: <?= htmlspecialchars($row2["hora_chegada"]) ?></h3>
                                <h3>Distancia: <?= htmlspecialchars($row['distancia'])?>km</h3>
                                <?php 

                                $sql = "SELECT * FROM viatura WHERE id_viatura = '" . htmlspecialchars($row2["id_viatura"]) . "'";
                                $result2 = executarQuery($sql);
                                if ($result2 && $result2->num_rows > 0) {
                                    $row3 = $result2->fetch_assoc();
                                ?>
                                <h3>Lugares disponíveis: <?= htmlspecialchars($row2['lugares_ocupados'])?> / <?= htmlspecialchars($row3['capacidade_lugares']) ?></h3>
                                <?php
                                } else {
                                    echo "<p>Nenhum autocarro encontrado.</p>";
                                }
                                ?>
                            </div>
                            <div class="note-footer">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <h3>Preço: <?= htmlspecialchars($row2["preco"]) ?>€</h3>
                                    <form method="POST" action="reserva.php">
                                        <button type="submit" name="comprar" class="btn">Comprar</button>
                                    </form>
                                    <?php 
                                        

                                    ?>
                                </div>
                            </div>
                            <br>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<p>Nenhuma viagem encontrada.</p>";
                        }
                        ?>
                    <?php
                    }
                } else {
                    echo "<p>Nenhuma viagem encontrada.</p>";

                    ?>
                    <button class="btn" onclick="window.location.href='rotas.php'">Voltar Atrás</button>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>