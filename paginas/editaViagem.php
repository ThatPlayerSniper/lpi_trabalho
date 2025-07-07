<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o utilizador é admin, caso contrário redireciona
//seNaoAdmin();

// Validar o ID da viagem
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da viagem não especificado.");
}
$id_viagem = intval($_GET['id']);

// Processar a eliminação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Eliminar bilhetes associados
    executarQuery("DELETE FROM bilhete WHERE id_viagem = $id_viagem");

    // Eliminar a viagem
    $res = executarQuery("DELETE FROM viagem WHERE id_viagem = $id_viagem");

    if ($res) {
        header("Location: editarViagens.php?msg=viagem_eliminada");
        exit();
    } else {
        $mensagem = "Erro ao eliminar a viagem.";
    }
}

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['eliminar'])) {
    $data = $_POST['data_viagem'];
    $partida = $_POST['hora_partida'];
    $chegada = $_POST['hora_chegada'];
    $preco = floatval($_POST['preco']);
    $id_viatura = intval($_POST['id_viatura']);
    $id_rota = intval($_POST['id_rota']);

    $sql = "UPDATE viagem SET 
                data_viagem = '$data',
                hora_partida = '$partida',
                hora_chegada = '$chegada',
                preco = $preco,
                id_viatura = $id_viatura,
                id_rota = $id_rota
            WHERE id_viagem = $id_viagem";
    $res = executarQuery($sql);

    $mensagem = $res ? "Viagem atualizada com sucesso!" : "Erro ao atualizar viagem.";
}

// Obter dados atuais da viagem
$viagem = mysqli_fetch_assoc(executarQuery("SELECT * FROM viagem WHERE id_viagem = $id_viagem"));
if (!$viagem) die("Viagem não encontrada.");

$viaturas = executarQuery("SELECT * FROM viatura");
$rotas = executarQuery("SELECT * FROM rota");
?>



<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonte personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos -->
    <link rel="stylesheet" href="criaRota.css">
</head>
<body>
<?php
// Inclusão da barra de navegação
require_once "./nav.php";
?>
<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Editar Viagem</h1>

                <form method="POST">
                    <div class="input-container">
                        <!-- Campo para data da viagem -->
                        <label class="Letras">Data da Viagem:</label>
                        <input class="input-field" type="date" name="data_viagem" required><br>
                        <!-- Campo para hora de partida -->
                        <label class="Letras">Hora de Partida:</label>
                        <input class="input-field" type="time" name="hora_partida" required><br>
                        <!-- Campo para hora de chegada -->
                        <label class="Letras">Hora de Chegada:</label>
                        <input class="input-field" type="time" name="hora_chegada" required><br>
                        <!-- Campo para preço -->
                        <label class="Letras">Preço:</label>
                        <input class="input-field" type="number" name="preco" step="0.01" placeholder="0.00" required><br>
                        <!-- Seleção de veículo -->
                        <label class="Letras">Veículo:</label>
                        <select class="input-field" name="veiculo_id">
                            <?php
                            // Buscar veículos disponíveis da base de dados
                            $veiculos = executarQuery("SELECT * FROM viatura");
                            while ($veiculo = mysqli_fetch_assoc($veiculos)) {
                                // Adiciona cada veículo como opção no select
                                echo '<option class="turnBlack" value="' . htmlspecialchars($veiculo['id_viatura']) . '">' . htmlspecialchars($veiculo['matricula']) . '</option>';
                            }
                            ?>
                        </select><br>
                    </div>
                    <!-- Botão de submissão do formulário -->
                    <input class="input-submit" type="submit" value="Submeter"><br><br>
                    <!-- Link para voltar atrás -->
                    <a class="turnWhite" href="rota.php">Voltar atrás</a>

                </form>

                <?php
                // Verifica se o formulário foi submetido e se todos os campos necessários estão presentes
                if (
                    $_SERVER["REQUEST_METHOD"] == "POST" &&
                    isset($_POST["veiculo_id"]) &&
                    isset($_POST["data_viagem"]) &&
                    isset($_POST["hora_partida"]) &&
                    isset($_POST["hora_chegada"]) &&
                    isset($_POST["preco"])
                ) {

                    // Recolhe os dados do formulário
                    $id_viatura = intval($_POST['veiculo_id']);
                    $data_Viagem = $_POST['data_viagem'];
                    $hora_partida = $_POST['hora_partida'];
                    $hora_chegada = $_POST['hora_chegada'];
                    $preco = floatval($_POST['preco']);
                    $id_rota = $_POST['id_rota'];

                    // Query para inserir nova viagem na base de dados
                    $sql = "INSERT INTO viagem (data_viagem, hora_partida, hora_chegada, preco, id_viatura, id_rota) 
            VALUES ('$data_Viagem', '$hora_partida', '$hora_chegada', $preco, $id_viatura, $id_rota)";

                    // Executa a query
                    $resultado = executarQuery($sql);

                    // Mensagem de sucesso ou erro
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