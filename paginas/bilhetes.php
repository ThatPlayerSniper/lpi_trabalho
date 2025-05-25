<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="transacoes.css">
</head>
<?php
require_once "./nav.php";
$utilizador  = getUser();

?>


<body>
    <div style="text-align: center;">
        <h1>Transações de <?= $utilizador['nome'] ?></h1>
    </div>
    <div class="big-box">
        <br>
        <?php
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
        WHERE b.id_utilizador = " . (int)$utilizador['id_utilizador'] . "
        ORDER BY b.data_compra ASC 
        LIMIT 10";
        $resultado = executarQuery($sql);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
        ?>
                <div class='note-card'>
                    <div class='note-header'>
                        <h2>Bilhete ID(Código Unico): <?= htmlspecialchars($row['id_bilhete']) ?></h2>
                    </div>
                    <div class='note-body'>
                        <p>Viagem: <?= htmlspecialchars($row['origem']) ?> --&gt; <?= htmlspecialchars($row['destino']) ?></p>
                        <p>Preço: <?= number_format($row['preco'], 2, ',', '.') ?>€</p>
                        <p>Partida <?= htmlspecialchars($row['hora_partida']) ?> minutos</p>
                        <p>Chegada <?= htmlspecialchars($row['hora_partida']) ?> minutos</p>
                        <p>Distância: <?= htmlspecialchars($row['distancia']) ?> km</p>
                        <p>Estado: <?=htmlspecialchars($row['estado_bilhete']) ?></p>
                        <p>Matricula</p>
                    </div>
                    <div class='note-footer'>
                        <span>
                            Id: <?= htmlspecialchars($row['id_utilizador']) ?> |
                            Nome do Cliente: <?= htmlspecialchars($row['nome_cliente']) ?>
                        </span>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Nenhum alerta encontrado.</p>";
        }
        ?>
    </div>
</body>

</html>