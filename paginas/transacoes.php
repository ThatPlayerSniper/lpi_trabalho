<?php
// Inclui o ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";
// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já têm uma sessão iniciada caso não tenha cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verifica se o utilizador tem permissão para aceder à página (admin, funcionário ou cliente)
if (seForAdminNR() == false && seForFunNR() == false && seForClienteNR() == false) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Ficheiro de estilos CSS para transações -->
    <link rel="stylesheet" href="transacoes.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
// Obtém os dados do utilizador autenticado
$utilizador  = getUser();

?>


<body>
    <div style="text-align: center;">
        <!-- Título com o nome do utilizador -->
        <h1>Transações de <?= $utilizador['nome'] ?></h1>
    </div>
    <div class="big-box">
        <br>
        <?php
        // Query para buscar as 10 transações mais recentes do utilizador
        $sql = "SELECT * FROM transacoes WHERE id_utilizador = '" . $utilizador['id_utilizador'] . "' ORDER BY data_transacao DESC LIMIT 10";
        $resultado = executarQuery($sql);
        // Verifica se existem transações
        if ($resultado->num_rows > 0) {
            // Ciclo para mostrar cada transação
            while ($transacao = $resultado->fetch_assoc()) {
        ?>
                <div class='note-card'>
                    <div class='note-header'>
                        <!-- Mostra o ID da transação -->
                        <h2>Transação ID: <?= htmlspecialchars($transacao['id_transacao']) ?></h2>
                    </div>
                    <div class='note-body'>
                        <!-- Mostra o tipo de transação -->
                        <span class='note-type'>Tipo: <?= htmlspecialchars($transacao['tipo_transacao']) ?></span>
                        <!-- Mostra o valor da transação -->
                        <p>Valor: <?= htmlspecialchars($transacao['valor']) ?></p>
                        <!-- Mostra o saldo após a transação -->
                        <p>Saldo novo: <?= htmlspecialchars($transacao['saldo_apos_transacao']) ?> </p>
                    </div>
                    <div class='note-footer'>
                        <span>
                            <!-- Mostra a data da transação -->
                            <p>Data: <?= htmlspecialchars($transacao['data_transacao']) ?></p>
                        </span>
                    </div>
                </div>
        <?php
            }
        } else {
            // Mensagem caso não existam transações
            echo "<p>Nenhuma tranasação encontrado.</p>";
        }
        ?>
    </div>
</body>

</html>