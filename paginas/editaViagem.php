<?php
require_once '../basedados/basedados.h';
define('INCLUDE_CHECK', true);
require_once "./auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
seNaoAdmin();

// Obter ID da viagem a editar
if (!isset($_GET['id_viagem']) || empty($_GET['id_viagem'])) {
    die("ID da viagem não especificado.");
}

$id_viagem = intval($_GET['id_viagem']);

// Processar a eliminação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Eliminar bilhetes associados
    $sql_bilhetes = "DELETE FROM bilhete WHERE id_viagem = $id_viagem";
    $res_bilhetes = executarQuery($sql_bilhetes);

    // Eliminar a viagem
    $sql_viagem = "DELETE FROM viagem WHERE id_viagem= $id_viagem";
    $res_viagem = executarQuery($sql_viagem);
    if ($res_viagem) {
        header("Location: viagem.php?msg=viagem_eliminada");
        exit();
    } else {
        $mensagem = "Erro ao eliminar a viagem.";
    }
}

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST["data_viagem"]) || empty($_POST["hora_partida"]) || empty($_POST["hora_chegada"]) || empty($_POST["preco"]) || empty($_POST["veiculo_id"])) {
        echo '<label class="turnWhite">Existem campos por preecher</label>';
    }
    if (isset($_POST["data_viagem"]) && isset($_POST["hora_partida"]) && isset($_POST["hora_chegada"]) && isset($_POST["preco"]) && isset($_POST["veiculo_id"])) {
        $data = $_POST['data_viagem'] ? $_POST['data_viagem'] : '';
        $partida = $_POST['hora_partida'] ? $_POST['hora_partida'] : '';
        $chegada = $_POST['hora_chegada'] ? $_POST['hora_chegada'] : '';
        $preco = floatval($_POST['preco']) ? $_POST['preco'] : '';
        $id_viatura = intval($_POST['veiculo_id']) ? $_POST['veiculo_id'] : '';

        $data = escapeString($data);
        $partida = escapeString($partida);
        $chegada = escapeString($chegada);
        $preco = escapeString($preco);
        $id_viatura = escapeString($id_viatura);

        $sql = "UPDATE viagem SET 
                    data_viagem = '$data',
                    hora_partida = '$partida',
                    hora_chegada = '$chegada',
                    preco = '$preco',
                    id_viatura = '$id_viatura'
                WHERE id_viagem = $id_viagem";

        $resultado = executarQuery($sql);

        if ($resultado) {
            $mensagem = "Viagem atualizada com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar viagem.";
        }
    }
}

// Obter dados atuais da viagem
$sql = "SELECT * FROM viagem WHERE id_viagem = $id_viagem";
$resultado = executarQuery($sql);

if (mysqli_num_rows($resultado) != 1) {
    die("Viagem não encontrada.");
}

$viagem = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criaRota.css">
</head>

<body>
    <?php require_once "./nav.php"; ?>

    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Editar Viagem</h1>

                <?php if (!empty($mensagem)) {
                    echo "<p class='turnWhite'>$mensagem</p>";
                } ?>

                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Data da Viagem:</label>
                        <input class="input-field" type="date" name="data_viagem" value="<?= htmlspecialchars($viagem['data_viagem']) ?>" required><br>
                        <label class="Letras">Hora de Partida:</label>
                        <input class="input-field" type="time" name="hora_partida" value="<?= htmlspecialchars($viagem['hora_partida']) ?>" required><br>
                        <label class="Letras">Hora de Chegada:</label>
                        <input class="input-field" type="time" name="hora_chegada" value="<?= htmlspecialchars($viagem['hora_chegada']) ?>" required><br>
                        <label class="Letras">Preço:</label>
                        <input class="input-field" type="number" name="preco" step="0.01" placeholder="0.00" value="<?= htmlspecialchars($viagem['preco']) ?>" required><br>
                        <label class="Letras">Veículo:</label>
                        <select class="input-field" name="veiculo_id">
                            <?php
                            // Buscar veículos disponíveis da base de dados
                            $veiculos = executarQuery("SELECT * FROM viatura");
                            while ($veiculo = mysqli_fetch_assoc($veiculos)) {
                                //o já selecionado
                                $selected = ($veiculo['id_viatura'] == $viagem['id_viatura']) ? 'selected' : '';
                                // Adiciona cada veículo como opção no select
                                echo '<option class="turnBlack" value="' . htmlspecialchars($veiculo['id_viatura']) . '" ' . $selected . '>' . htmlspecialchars($veiculo['matricula']) . '</option>';
                            }
                            ?>
                        </select><br>
                    </div>
                    <input class="input-submit" type="submit" value="Guardar Alterações"><br><br>
                    <button class="input-submit" type="submit" name="eliminar" value="1" onclick="return confirm('Tem a certeza que deseja eliminar esta viagem?');">Eliminar Viagem</button><br><br>
                    <a class="turnWhite" href="rota.php">Voltar à lista de Rotas</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>