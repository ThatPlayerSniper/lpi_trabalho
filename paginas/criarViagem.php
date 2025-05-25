<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

seNaoAdmin();
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criaRota.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Criação de Viagens</h1>

                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Data da Viagem:</label>
                        <input class="input-field" type="date" name="data_viagem" required><br>
                        <label class="Letras">Hora de Partida:</label>
                        <input class="input-field" type="time" name="hora_partida" required><br>
                        <label class="Letras">Hora de Chegada:</label>
                        <input class="input-field" type="time" name="hora_chegada" required><br>
                        <label class="Letras">Preço:</label>
                        <input class="input-field" type="number" name="preco" step="0.01" placeholder="0.00" required><br>
                        <label class="Letras">Veículo:</label>
                        <select class="input-field" name="veiculo_id">
                            <?php
                            // Buscar veículos disponíveis da base de dados
                            $veiculos = executarQuery("SELECT * FROM viatura");
                            while ($veiculo = mysqli_fetch_assoc($veiculos)) {
                                echo '<option class="turnBlack" value="' . htmlspecialchars($veiculo['id_viatura']) . '">' . htmlspecialchars($veiculo['matricula']) . '</option>';
                            }
                            ?>
                        </select><br>
                    </div>
                    <input class="input-submit" type="submit" value="Submeter"><br><br>
                    <a class="turnWhite" href="rota.php">Voltar atrás</a>

                </form>

                <?php
                if (
                    $_SERVER["REQUEST_METHOD"] == "POST" &&
                    isset($_POST["veiculo_id"]) &&
                    isset($_POST["data_viagem"]) &&
                    isset($_POST["hora_partida"]) &&
                    isset($_POST["hora_chegada"]) &&
                    isset($_POST["preco"])
                ) {

                    $id_viatura = intval($_POST['veiculo_id']);
                    $data_Viagem = $_POST['data_viagem'];
                    $hora_partida = $_POST['hora_partida'];
                    $hora_chegada = $_POST['hora_chegada'];
                    $preco = floatval($_POST['preco']);

                    // You need to get the rota_id from somewhere - maybe add it as a hidden field or select in your form
                    // For now I'll assume it's 1, but you should modify this
                    $id_rota = 1;

                    $sql = "INSERT INTO viagem (data_viagem, hora_partida, hora_chegada, preco, id_viatura, id_rota) 
            VALUES ('$data_Viagem', '$hora_partida', '$hora_chegada', $preco, $id_viatura, $id_rota)";

                    $resultado = executarQuery($sql);

                    if ($resultado) {
                        echo "<p class='success-message'>Viagem criada com sucesso!</p>";
                    } else {
                        echo "<p class='error-message'>Erro ao criar viagem.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>