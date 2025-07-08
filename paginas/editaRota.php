<?php
require_once '../basedados/basedados.h';
define('INCLUDE_CHECK', true);
require_once "./auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
seNaoAdmin();

// Obter ID da rota a editar
if (!isset($_GET['id_rota']) || empty($_GET['id_rota'])) {
    die("ID da rota não especificado.");
}

$id_rota = intval($_GET['id_rota']);

// Processar a eliminação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Eliminar bilhetes que estão ligados às viagens desta rota
    $sql_bilhetes = "DELETE FROM bilhete WHERE id_viagem IN (
                        SELECT id_viagem FROM viagem WHERE id_rota = $id_rota
                     )";
    $result_bilhetes = executarQuery($sql_bilhetes);

    // Eliminar viagens associadas à rota
    $sql_viagens = "DELETE FROM viagem WHERE id_rota = $id_rota";
    $result_viagens = executarQuery($sql_viagens);

    // Agora sim, eliminar a rota
    $sql_rota = "DELETE FROM rota WHERE id_rota = $id_rota";
    $result_rota = executarQuery($sql_rota);

    if ($result_rota) {
        header("Location: rota.php?msg=rota_eliminada");
        exit();
    } else {
        $mensagem = "Erro ao eliminar a rota.";
    }
}


// Processar o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST["origem"]) || empty($_POST["destino"]) || empty($_POST["duracao"]) || empty($_POST["distancia"])) {
        echo '<label class="turnWhite">Existem campos por preecher</label>';
    }
    if (isset($_POST["origem"]) && isset($_POST["destino"]) && isset($_POST["duracao"]) && isset($_POST["distancia"])) {

        $origem = $_POST["origem"] ? $_POST['origem'] : '';
        $destino = $_POST["destino"] ? $_POST['destino'] : '';
        $duracao = $_POST["duracao"] ? $_POST['duracao'] : '';
        $distancia = $_POST["distancia"] ? $_POST['distancia'] : '';


        $origem = escapeString($origem);
        $destino = escapeString($destino);
        $duracao = escapeString($duracao);
        $distancia = escapeString($distancia);

        $sql = "UPDATE rota SET 
                    origem = '$origem',
                    destino = '$destino',
                    tempo_viagem = '$duracao',
                    distancia = '$distancia'
                WHERE id_rota = $id_rota";

        $resultado = executarQuery($sql);

        if ($resultado) {
            $mensagem = "Rota atualizada com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar rota.";
        }
    }
}

// Obter dados atuais da rota
$sql = "SELECT * FROM rota WHERE id_rota = $id_rota";
$resultado = executarQuery($sql);

if (mysqli_num_rows($resultado) != 1) {
    die("Rota não encontrada.");
}

$rota = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criaRota.css">
</head>

<body>
    <?php require_once "./nav.php"; ?>

    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Editar Rota</h1>

                <?php if (!empty($mensagem)) {
                    echo "<p class='turnWhite'>$mensagem</p>";
                } ?>

                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Origem:</label>
                        <input class="input-field" type="text" name="origem" value="<?= htmlspecialchars($rota['origem']) ?>" required><br>

                        <label class="Letras">Destino:</label>
                        <input class="input-field" type="text" name="destino" value="<?= htmlspecialchars($rota['destino']) ?>" required><br>

                        <label class="Letras">Duração:</label>
                        <input class="input-field" type="text" name="duracao" value="<?= htmlspecialchars($rota['tempo_viagem']) ?>" required><br>

                        <label class="Letras">Distância (km):</label>
                        <input class="input-field" type="text" name="distancia" value="<?= htmlspecialchars($rota['distancia']) ?>" required><br><br>
                    </div>

                    <input class="input-submit" type="submit" value="Guardar Alterações"><br><br>
                    <button class="input-submit" type="submit" name="eliminar" value="1" onclick="return confirm('Tem a certeza que deseja eliminar esta rota?');">Eliminar Rota</button><br><br>
                    <a class="turnWhite" href="rota.php">Voltar à lista de Rotas</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>