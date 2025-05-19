<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
    <?php
    require_once "./nav.php";
    $utilizador  = getUser();
    ?>

<body>
    <div class="background">
        <div class="card">
            <h1>Carteira</h1>
            <div class="header">
                <h2>Conta: <span><?= ($utilizador['nome']) ?></span></h2>
                <h2>saldo: <span><?= ($utilizador['saldo_atual']) ?>€</span></h2>
                <form method="POST">
                    <input type="number" name="number" step="0.01" placeholder="0.00" required>
                    <br><br>
                    <input type="submit" name="minus" value="Retirar">
                    <input type="submit" name="add" value="Adicionar">
                </form>
                <?php
                $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
                $minus = isset($_POST['minus']) ? $_POST['minus'] : '';
                $add = isset($_POST['add']) ? $_POST['add'] : '';

                $valor_car = 0.00;

                $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = " . $_SESSION['user_id'];
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $valor_car = floatval($row['saldo_atual']);

                    if (!empty($minus)) {
                        if ($valor <= $valor_car) {

                            $valorRetirar = $valor;
                            $novoSaldo = $valor_car - $valorRetirar;

                            // Adiciona o valor à carteira do utilizador
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                            executarQuery($sql2);

                            // Cria o registo da transação
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                            VALUES (" . (int)$row['id_utilizador'] . ", 'levantamento', " . floatval($valorRetirar) . ", $novoSaldo)";
                            executarQuery($sqlInsert);

                            // Verifica se o utilizador Felix existe
                            $sql = "SELECT u.*, c.saldo_atual 
                            FROM utilizador u
                            INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                            WHERE u.id_utilizador = '1'";
                            $resultado = executarQuery($sql);

                            if ($resultado->num_rows > 0) {
                                $row = $resultado->fetch_assoc();

                                $valorFelix = floatval($row['saldo_atual']);
                                $novoSaldoFelix = $valorFelix - $valor;

                                // Adiciona o valor à carteira da conta felixbus
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                executarQuery($sql2);

                                //Cria o registo da transação
                                $sql = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                VALUES (1, 'reembolso', " . floatval($valor) . ", $novoSaldoFelix)";
                                executarQuery($sql);
                            }
                            header("Location: saldo_gestao.php");
                            exit;
                        } else {
                            echo "<h2>ERRO: Saldo insuficiente</h2>";
                            exit;
                        }
                    }

                    if (!empty($add)) {

                        $valorAdicionar = $valor;
                        $novoSaldo = $valor_car + $valorAdicionar;

                        // Adiciona o valor à carteira do utilizador
                        $sql5 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                        executarQuery($sql5);


                        $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                        VALUES (" . (int)$row['id_utilizador'] . ", 'deposito', " . floatval($valorAdicionar) . ", $novoSaldo)";
                        executarQuery($sqlInsert);

                        // Verifica se o utilizador Felix existe
                        $sql = "SELECT u.*, c.saldo_atual 
                        FROM utilizador u
                        INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                        WHERE u.id_utilizador = '1'";
                        $resultado = executarQuery($sql);

                        // Verifica se o utilizador Felix existe
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();

                            $valorFelix = floatval($row['saldo_atual']);
                            $novoSaldoFelix = $valorFelix + $valor;

                            // Adiciona o valor à carteira da conta felixbus
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                            executarQuery($sql2);

                            //Cria o registo da transação
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (1, 'transferencia', " . floatval($valor) . ", $novoSaldoFelix)";
                            executarQuery($sqlInsert);
                        }
                        header("Location: saldo_gestao.php");
                        exit;
                    }
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>