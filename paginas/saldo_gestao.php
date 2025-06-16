<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";

// Inicia a sessão se ainda não estiver iniciada
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
                // Obtém valores do formulário
                $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
                $minus = isset($_POST['minus']) ? $_POST['minus'] : '';
                $add = isset($_POST['add']) ? $_POST['add'] : '';

                $valor_car = 0.00;

                // Busca saldo atual do utilizador
                $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = " . $_SESSION['user_id'];
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $valor_car = floatval($row['saldo_atual']);

                    // Se não for a conta FelixBus
                    if ($row['nome'] != 'FelixBus') {

                        // Retirar saldo
                        if (!empty($minus)) {
                            if ($valor <= $valor_car) {

                                $valorRetirar = $valor;
                                $novoSaldo = $valor_car - $valorRetirar;

                                // Atualiza saldo do utilizador
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                                executarQuery($sql2);

                                // Regista transação do utilizador
                                $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                            VALUES (" . (int)$row['id_utilizador'] . ", 'levantamento', " . floatval($valorRetirar) . ", $novoSaldo)";
                                executarQuery($sqlInsert);

                                // Atualiza saldo da conta FelixBus
                                $sql = "SELECT u.*, c.saldo_atual 
                            FROM utilizador u
                            INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                            WHERE u.id_utilizador = '1'";
                                $resultado = executarQuery($sql);

                                if ($resultado->num_rows > 0) {
                                    $row = $resultado->fetch_assoc();

                                    $valorFelix = floatval($row['saldo_atual']);
                                    $novoSaldoFelix = $valorFelix - $valor;

                                    // Atualiza saldo FelixBus
                                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                    executarQuery($sql2);

                                    // Regista transação FelixBus
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

                        // Adicionar saldo
                        if (!empty($add)) {

                            $valorAdicionar = $valor;
                            $novoSaldo = $valor_car + $valorAdicionar;

                            // Atualiza saldo do utilizador
                            $sql5 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$utilizador['id_utilizador'];
                            executarQuery($sql5);

                            // Regista transação do utilizador
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                        VALUES (" . (int)$row['id_utilizador'] . ", 'deposito', " . floatval($valorAdicionar) . ", $novoSaldo)";
                            executarQuery($sqlInsert);

                            // Atualiza saldo da conta FelixBus
                            $sql = "SELECT u.*, c.saldo_atual 
                        FROM utilizador u
                        INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                        WHERE u.id_utilizador = '1'";
                            $resultado = executarQuery($sql);

                            if ($resultado->num_rows > 0) {
                                $row = $resultado->fetch_assoc();

                                $valorFelix = floatval($row['saldo_atual']);
                                $novoSaldoFelix = $valorFelix + $valor;

                                // Atualiza saldo FelixBus
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                executarQuery($sql2);

                                // Regista transação FelixBus
                                $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (1, 'transferencia', " . floatval($valor) . ", $novoSaldoFelix)";
                                executarQuery($sqlInsert);
                            }
                            header("Location: saldo_gestao.php");
                            exit;
                        }

                    // Caso seja a conta FelixBus
                    }else{

                        // Retirar saldo da FelixBus
                        if (!empty($minus)) {
                            if ($valor <= $valor_car) {

                                $valorRetirar = $valor;
                                $novoSaldo = $valor_car - $valorRetirar;

                                // Atualiza saldo da conta FelixBus
                                $sql = "SELECT u.*, c.saldo_atual 
                            FROM utilizador u
                            INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                            WHERE u.id_utilizador = '1'";
                                $resultado = executarQuery($sql);

                                if ($resultado->num_rows > 0) {
                                    $row = $resultado->fetch_assoc();

                                    $valorFelix = floatval($row['saldo_atual']);
                                    $novoSaldoFelix = $valorFelix - $valor;

                                    // Atualiza saldo da conta FelixBus
                                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                    executarQuery($sql2);

                                    // Regista transação da conta FelixBus
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

                        // Adicionar saldo à conta FelixBus
                        if (!empty($add)) {

                            $valorAdicionar = $valor;
                            $novoSaldo = $valor_car + $valorAdicionar;

                            $sql = "SELECT u.*, c.saldo_atual 
                        FROM utilizador u
                        INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                        WHERE u.id_utilizador = '1'";
                            $resultado = executarQuery($sql);

                            if ($resultado->num_rows > 0) {
                                $row = $resultado->fetch_assoc();

                                $valorFelix = floatval($row['saldo_atual']);
                                $novoSaldoFelix = $valorFelix + $valor;

                                // Atualiza saldo da conta FelixBus
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                executarQuery($sql2);

                                // Regista transação da conta FelixBus
                                $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (1, 'transferencia', " . floatval($valor) . ", $novoSaldoFelix)";
                                executarQuery($sqlInsert);
                            }
                            header("Location: saldo_gestao.php");
                            exit;
                        }

                    }
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>