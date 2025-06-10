<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
// Verifica se já têm uma sessão iniciada caso não tenho cria uma
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
    <link rel="stylesheet" href="viagem.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="big-box">
        <div class="container">
            <div class="right-container">
                <h1>Viagens</h1>
                <?php
                $utilizador = getUser();
                $id_rota = isset($_POST['rota']) ? $_POST['rota'] : '';

                // Exibir mensagens de sucesso/erro se existirem
                if (isset($_GET['success'])) {
                    echo "<div class='alert success'>" . htmlspecialchars($_GET['success']) . "</div>";
                }
                if (isset($_GET['error'])) {
                    echo "<div class='alert error'>" . htmlspecialchars($_GET['error']) . "</div>";
                }

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
                        <div>
                            <?php
                            if (seForAdminNR()) {
                                // Debug output - remove this after testing
                                echo "<!-- User is admin -->";
                            ?>
                                <form method="POST" action="criarViagem.php">
                                    <input type="hidden" name="rota" value="<?= htmlspecialchars($id_rota) ?>">
                                    <button type="submit" class="btn">Criar Viagens</button>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                        <h2>Viagens disponíveis:</h2>
                        <?php
                        $sql = "SELECT v.*, vt.capacidade_lugares 
                                FROM viagem v 
                                JOIN viatura vt ON v.id_viatura = vt.id_viatura 
                                WHERE v.id_rota = '$id_rota'";
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
                                        <h3>Distancia: <?= htmlspecialchars($row['distancia']) ?>km</h3>
                                        <h3>Lugares disponíveis: <?= htmlspecialchars($row2['lugares_ocupados']) ?> / <?= htmlspecialchars($row2['capacidade_lugares']) ?></h3>
                                    </div>
                                    <div class="note-footer">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <h3>Preço: <?= htmlspecialchars($row2["preco"]) ?>€</h3>
                                            <form method="POST" action="criarbilhete.php">
                                                <?php 
                                                if (
                                                    ($row2['lugares_ocupados'] < $row2['capacidade_lugares']) && 
                                                    (seForAdminNR() || seForFunNR() || seForClienteNR())
                                                ) { 
                                                ?>
                                                    <input type="hidden" name="id_viagem" value="<?= htmlspecialchars($row2["id_viagem"]) ?>">
                                                    <input type="hidden" name="rota" value="<?= $id_rota ?>">
                                                    <?php if(seForFunNR() || seForAdminNR()) { ?>
                                                    <label for="id_utilizador">ID do Utilizador:</label>
                                                    <input type="text" name="id_utilizador">
                                                    <?php } ?>
                                                    <button type="submit" name="comprar" class="btn">Comprar</button>
                                                <?php 
                                                } else if ($row2['lugares_ocupados'] >= $row2['capacidade_lugares']) {
                                                    echo "<p>Viagem cheia</p>";
                                                } 
                                                else {
                                                    echo "<p>Não tem permissão para comprar bilhetes</p>";
                                                }

                                                ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>Nenhuma viagem encontrada.</p>";
                        }
                        ?>
                    <?php
                    }
                } 
                    ?>
                    <button class="btn" onclick="window.location.href='rota.php'">Voltar Atrás</button>
            </div>
        </div>
    </div>
</body>

</html>