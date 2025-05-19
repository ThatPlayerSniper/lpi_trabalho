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
        $sql = "SELECT * FROM transacoes WHERE id_utilizador = '" . $utilizador['id_utilizador'] . "' LIMIT 10";
        $resultado = executarQuery($sql);
        if ($resultado->num_rows > 0) {
            while ($transacao = $resultado->fetch_assoc()) {
        ?>
                <div class='note-card'>
                    <div class='note-header'>
                        <h2>Transação ID: <?= htmlspecialchars($transacao['id_transacao']) ?></h2>
                    </div>
                    <div class='note-body'>
                        <span class='note-type'>Tipo: <?= htmlspecialchars($transacao['tipo_transacao']) ?></span>
                        <p>Valor: <?= htmlspecialchars($transacao['valor']) ?></p>
                        <p>Saldo novo: <?= htmlspecialchars($transacao['saldo_apos_transacao']) ?> </p>
                    </div>
                    <div class='note-footer'>
                        <span>
                            <p>Data: <?= htmlspecialchars($transacao['data_transacao']) ?></p>
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