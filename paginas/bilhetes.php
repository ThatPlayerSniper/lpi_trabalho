<?php
// Inclui o ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já existe uma sessão iniciada, caso não, inicia uma nova sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verifica se o utilizador tem permissão para aceder (admin, funcionário ou cliente)
// Se não tiver, redireciona para a página principal
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
        <h1>Bilhetes ativos de <?= $utilizador['nome'] ?></h1>
    </div>
    <div class="big-box">
        <br>
        <?php
        // Query para obter os bilhetes ativos do utilizador, com detalhes da viagem e rota
        $sql = "SELECT 
            b.*, 
            v.data_viagem, 
            v.estado, 
            v.hora_partida, 
            v.hora_chegada, 
            v.preco,
            r.origem, 
            r.destino, 
            r.tempo_viagem, 
            r.distancia
        FROM bilhete b
        INNER JOIN viagem v ON b.id_viagem = v.id_viagem
        INNER JOIN rota r ON b.id_rota = r.id_rota
        WHERE b.id_utilizador = " . (int)$utilizador['id_utilizador'] . " AND b.estado_bilhete = 'ativo'
        ORDER BY b.data_compra ASC 
        LIMIT 10";
        // Executa a query
        $resultado = executarQuery($sql);
        // Verifica se existem resultados
        if ($resultado->num_rows > 0) {
            // Itera sobre cada bilhete encontrado
            while ($row = $resultado->fetch_assoc()) {
        ?>
                <div class='note-card'>
                    <div class='note-header'>
                        <!-- Mostra o ID do bilhete -->
                        <h2>Bilhete ID(Código Unico): <?= htmlspecialchars($row['id_bilhete']) ?></h2>
                    </div>
                    <div class='note-body'>
                        <!-- Mostra detalhes da viagem -->
                        <p>Viagem: <?= htmlspecialchars($row['origem']) ?> --&gt; <?= htmlspecialchars($row['destino']) ?></p>
                        <p>Preço: <?= number_format($row['preco'], 2, ',', '.') ?>€</p>
                        <p>Partida <?= htmlspecialchars($row['hora_partida']) ?> </p>
                        <p>Chegada <?= htmlspecialchars($row['hora_partida']) ?> </p>
                        <p>Distância: <?= htmlspecialchars($row['distancia']) ?> km</p>
                        <p>Estado: <?=htmlspecialchars($row['estado_bilhete']) ?></p>
                    </div>
                    <div class='note-footer'>
                        <span>
                            <!-- Mostra o ID do utilizador e o nome do cliente -->
                            Id: <?= htmlspecialchars($row['id_utilizador']) ?> |
                            Nome do Cliente: <?= htmlspecialchars($row['nome_cliente']) ?>
                        </span>
                    </div>
                </div>
        <?php
            }
        } else {
            // Caso não existam bilhetes ativos, mostra mensagem
            echo "<p>Nenhum alerta encontrado.</p>";
        }
        ?>
    </div>
</body>

</html>