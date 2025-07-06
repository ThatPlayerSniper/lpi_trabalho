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
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos CSS para a página de criação de alertas -->
    <link rel="stylesheet" href="criaAlerta.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
?>

<body>
    <div class="caixa-background">
        <div class="caixa-protetora">
            <div class="caixa-sistema">
                <h1 class="turnWhite">Criação de Alertas</h1>
                <?php

                // Verifica se os campos obrigatórios estão vazios e mostra uma mensagem de aviso
                if (empty($_POST["tipo"]) || empty($_POST["descricao"])) {
                    echo '<label class="turnWhite">Existem campos por preecher</label>';
                }
                // Se os campos obrigatórios estiverem definidos, processa o formulário
                if (isset($_POST["tipo"]) && isset($_POST["descricao"])) {

                    // Obtém os valores do formulário ou define como string vazia se não existir
                    $tipo = $_POST["tipo"] ? $_POST['tipo'] : '';
                    $descricao = $_POST["descricao"] ? $_POST['descricao'] : '';
                    $data_fim = $_POST["data_fim"] ? $_POST['data_fim'] : '';
                    // Obtém o id do utilizador da sessão
                    $utilizador =  $_SESSION['user_id'];

                    // Escapa as strings para evitar SQL Injection
                    $tipo = escapeString($tipo);
                    $descricao = escapeString($descricao);
                    $data_fim = escapeString($data_fim);

                    // Query para inserir o alerta na base de dados
                    $sql = "INSERT INTO alertas(id_utilizador, tipo_alerta, descricao, data_expira) VALUES ('$utilizador','$tipo', '$descricao','$data_fim')";
                    $resultado = executarQuery($sql);
                }

                ?>
                <!-- Formulário para criação de alerta -->
                <form method="POST">
                    <div class="input-container">
                        <label class="Letras">Tipo de Alerta:</label>
                        <input type="radio" name="tipo" value="promocao"> Promoção<br>
                        <input type="radio" name="tipo" value="cancelamento"> Cancelamento<br>
                        <input type="radio" name="tipo" value="manutencao"> Manutenção<br>
                        <input type="radio" name="tipo" value="alteracao_rota"> Alteração de Rota<br>
                        <input type="radio" name="tipo" value="outro"> Outro Tipo<br>
                        <label class="Letras">Descrição:</label>
                        <input class="input-field" type="text" name="descricao" placeholder="De que se trata..."><br>
                        <label class="Letras">Data de Expiração:</label>
                        <input class="input-field" type="date" name="data_fim" placeholder="2025-10-24"><br>
                        <br>
                    </div>
                    <input class="input-submit" type="submit" value="Submeter"><br><br>
                    <a class="turnWhite" href="index.php">Não quer criar mais alertas? Clique aqui!</a>
                    <br>
                </form>
            </div>
        </div>
    </div>
</body>

</html>