<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já existe uma sessão iniciada, caso não, inicia uma nova sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Garante que apenas administradores podem aceder a esta página
seNaoAdmin();

if (!isset($_GET['id_alerta']) || empty($_GET['id_alerta'])) {
    die("ID do alerta não especificado.");
}
$id_alerta = intval($_GET['id_alerta']);

// Processar a eliminação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Eliminar o bilhete
    $sql_alerta = "DELETE FROM alertas WHERE id_alerta = $id_alerta";
    $result_alerta = executarQuery($sql_alerta);


    if ($result_alerta) {
        header("Location: alertas.php?msg=alerta_eliminado");
        exit();
    } else {
        $mensagem = "Erro ao eliminar o alerta.";
    }
}


// Processar o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (empty($_POST["tipo"]) || empty($_POST["descricao"]) || empty($_POST["data_expira"])) {
        echo '<label class="turnWhite">Existem campos por preecher</label>';
    }
    if (isset($_POST["tipo"]) && isset($_POST["descricao"]) && isset($_POST["data_expira"])) {

        $tipo = $_POST["tipo"] ? $_POST['tipo'] : '';
        $descricao = $_POST["descricao"] ? $_POST['descricao'] : '';
        $data_expira = $_POST["data_expira"] ? $_POST['data_expira'] : '';

        $tipo = escapeString($tipo);
        $descricao = escapeString($descricao);
        $data_expira = escapeString($data_expira);

        $sql = "UPDATE alertas SET 
                    tipo_alerta = '$tipo',
                    descricao = '$descricao',
                    data_expira = '$data_expira'
                WHERE id_alerta = $id_alerta";

        $resultado = executarQuery($sql);

        if ($resultado) {
            $mensagem = "Alerta atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar alerta.";
        }
    }
}

// Obter dados atuais da rota
$sql = "SELECT * FROM alertas WHERE id_alerta = $id_alerta";
$resultado = executarQuery($sql);

if (mysqli_num_rows($resultado) != 1) {
    die("Alerta não encontrado.");
}

$alerta = mysqli_fetch_assoc($resultado);

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="criaAlerta.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Editar Alerta</h1>

                <?php if (!empty($mensagem)) {
                echo "<p class='turnWhite'>$mensagem</p>";
                } ?>

                <!-- Formulário para edição de alerta -->
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Tipo de Alerta:</label>
                        <input type="radio" name="tipo" value="promocao" <?= $alerta['tipo_alerta'] === 'promocao' ? 'checked' : '' ?>> Promoção<br>
                        <input type="radio" name="tipo" value="cancelamento" <?= $alerta['tipo_alerta'] === 'cancelamento' ? 'checked' : '' ?>> Cancelamento<br>
                        <input type="radio" name="tipo" value="manutencao" <?= $alerta['tipo_alerta'] === 'manutencao' ? 'checked' : '' ?>>Manutenção<br>
                        <input type="radio" name="tipo" value="alteracao_rota" <?= $alerta['tipo_alerta'] === 'alteracao_rota' ? 'checked' : '' ?>> Alteração de Rota<br>
                        <input type="radio" name="tipo" value="outro" <?= $alerta['tipo_alerta'] === 'outro' ? 'checked' : '' ?>> Outro Tipo<br>
                        <label class="Letras">Descrição:</label>
                        <input class="input-field" type="text" name="descricao" placeholder="De que se trata..." value="<?= htmlspecialchars($alerta['descricao']) ?>" required> <br>
                        <label class="Letras">Data de Expiração:</label>
                        <input class="input-field" type="date" name="data_expira" placeholder="2025-10-24" value="<?= htmlspecialchars($alerta['data_expira']) ?>" required><br>
                        <br>
                    </div>
                    <input class="input-submit" type="submit" value="Guardar Alterações"><br><br>
                    <button class="input-submit" type="submit" name="eliminar" value="1" onclick="return confirm('Tem a certeza que deseja eliminar esta viagem?');">Eliminar Viagem</button><br><br>
                    <a class="turnWhite" href="alertas.php">Voltar à lista de Alertas</a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</body>

</html>