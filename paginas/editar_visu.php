<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (seForAdminNR() == false && seForFunNR() == false) {
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
</head>
<?php
require_once "./nav.php";
?>

<body>
    <div class="background">
        <div class="card">
            <?php

            $user_id = '';

            // Verifica se está recebe um novo utilizador via GET (vindo da página gestao_utili.php)
            if (isset($_GET['edit_uti']) && !empty($_GET['edit_uti'])) {
                $user_id = escapeString($_GET['edit_uti']);
                $_SESSION['current_edit_user'] = $user_id; // Atualiza a sessão com o novo usuário
            }
            // Verifica se recebe um utilizador pelo POST (do próprio formulário)
            elseif (isset($_POST['edit_uti']) && !empty($_POST['edit_uti'])) {
                $user_id = escapeString($_POST['edit_uti']);
                $_SESSION['current_edit_user'] = $user_id;
            }
            // Se não recebeu por GET nem POST, tenta usar o que está na sessão
            elseif (isset($_SESSION['current_edit_user'])) {
                $user_id = $_SESSION['current_edit_user'];
            }

            // Verifica se o formulário foi enviado para atualização de perfil
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['nome']) || isset($_POST['cargoMudar']))) {
                // sanatiza os dados recebidos
                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $pass = isset($_POST['pass']) ? escapeString($_POST['pass']) : '';
                $cargo = isset($_POST['cargoMudar']) ? escapeString($_POST['cargoMudar']) : '';

                // Verifica se os campos estão preenchidos
                if (!empty($nome) && !empty($endereco) && !empty($user_id) && !empty($pass) && !empty($cargo)) {
                    $passHash = hash('sha256', $pass);

                    // Atualiza os dados do utilizador
                    $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco', secretpass = '$passHash', cargo = '$cargo' WHERE id_utilizador = '$user_id'";
                    executarQuery($sql);
                    echo "<p>Perfil atualizado com sucesso!</p>";
                } else {
                    echo "<p>Têm de preencher todos os campos</p>";
                }
            }

            // Processamento das transações financeiras
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['minus']) || isset($_POST['add']))) {
                $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
                $minus = isset($_POST['minus']) ? true : false;
                $add = isset($_POST['add']) ? true : false;

                //Inner join para obter os dados do utilizador e carteira
                $sql = "SELECT u.*, c.saldo_atual 
                    FROM utilizador u
                    INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                    WHERE u.id_utilizador = '$user_id'";

                //Executar Query
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $valor_car = floatval($row['saldo_atual']);

                    // Se for a retirar
                    if ($minus) {
                        if ($valor <= $valor_car) {
                            $valorRetirar = $valor;
                            $novoSaldo = $valor_car - $valorRetirar;

                            // Adiciona o valor à carteira do utilizador
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = '$user_id'";
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
                            $resultadoFelix = executarQuery($sql);

                            if ($resultadoFelix && $resultadoFelix->num_rows > 0) {
                                $rowFelix = $resultadoFelix->fetch_assoc();

                                //vai buscar o valor atual da conta felix e faz as contas para obter o novo saldo
                                $valorFelix = floatval($rowFelix['saldo_atual']);
                                $novoSaldoFelix = $valorFelix - $valor;

                                // Adiciona o valor à carteira da conta felixbus
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                executarQuery($sql2);

                                //Cria o registo da transação
                                $sql = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                    VALUES (1, 'reembolso', " . floatval($valor) . ", $novoSaldoFelix)";
                                executarQuery($sql);
                            }

                            echo "<p>Valor retirado com sucesso!</p>";
                        } else {
                            echo "<h2>ERRO: Saldo insuficiente</h2>";
                        }
                    }

                    // se for a adicionar
                    if ($add) {
                        $valorAdicionar = $valor;
                        $novoSaldo = $valor_car + $valorAdicionar;

                        // Adiciona o valor à carteira do utilizador
                        $sql5 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = '$user_id'";
                        executarQuery($sql5);

                        $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                            VALUES (" . (int)$row['id_utilizador'] . ", 'deposito', " . floatval($valorAdicionar) . ", $novoSaldo)";
                        executarQuery($sqlInsert);

                        // Verifica se o utilizador Felix existe
                        $sql = "SELECT u.*, c.saldo_atual 
                            FROM utilizador u
                            INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                            WHERE u.id_utilizador = '1'";
                        $resultadoFelix = executarQuery($sql);

                        // Verifica se o utilizador Felix existe
                        if ($resultadoFelix && $resultadoFelix->num_rows > 0) {
                            $rowFelix = $resultadoFelix->fetch_assoc();

                            //vai buscar o valor atual da conta felix e faz as contas para obter o novo saldo
                            $valorFelix = floatval($rowFelix['saldo_atual']);
                            $novoSaldoFelix = $valorFelix + $valor;

                            // Adiciona o valor à carteira da conta felixbus
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                            executarQuery($sql2);

                            //Cria o registo da transação
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                VALUES (1, 'transferencia', " . floatval($valor) . ", $novoSaldoFelix)";
                            executarQuery($sqlInsert);
                        }

                        echo "<p>Valor adicionado com sucesso!</p>";
                    }
                }
            }
            ?>

            <form method="post" class="header">
                <input type="hidden" name="edit_uti" value="<?php echo $user_id; ?>">
                <?php
                // select para obter os dados do utilizador e carteira
                if (!empty($user_id)) {


                    $sql = "SELECT u.*, c.saldo_atual 
                    FROM utilizador u
                    INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                    WHERE u.id_utilizador = '$user_id'";
                    $resultado = executarQuery($sql);

                    if ($resultado && $resultado->num_rows > 0) {
                        $row = $resultado->fetch_assoc();
                ?>      
                        <?php if (seForFunNR()) : ?>
                            <h2>Conta: <span><?= htmlspecialchars($row['nome']) ?></span></h2>
                        <?php endif; ?>
                        <?php if (seForAdminNR()) : ?>
                            <label for="id_utilizador">Nome:</label>
                            <input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>">
                            <br><br>
                            <label for="endereco">Email:</label>
                            <input type="email" name="endereco" id="endereco" value="<?= htmlspecialchars($row['endereco']) ?>">
                            <br><br>
                            <label for="Senha">Senha:</label>
                            <input type="password" name="pass" id="pass" value="<?= htmlspecialchars($row['secretpass']) ?>">
                            <br><br>
                            <div>
                                <label>Cargo:</label>
                                <select name="cargoMudar">
                                    <option value="cliente" <?= $row['cargo'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                    <option value="funcionario" <?= $row['cargo'] == 'funcionario' ? 'selected' : '' ?>>Funcionario</option>
                                    <option value="admin" <?= $row['cargo'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn">Guardar Alterações</button>
                        <?php endif; ?>
                <?php
                    } else {
                        echo "<h3>O utilizador não foi encontrado!</h3>";
                    }
                } else {
                    echo "<h3>Nenhum utilizador selecionado!</h3>";
                }
                ?>
            </form>
            <br>

            <?php

            //Mostra a balança do utilizador
            if (!empty($user_id)) {
                $sql = "SELECT u.*, c.saldo_atual 
                    FROM utilizador u
                    INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                    WHERE u.id_utilizador = '$user_id'";
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
            ?>
                    <h3>Saldo: <span><?= $row['saldo_atual'] ?></span></h3>
            <?php
                }
            }
            ?>
            <!-- Envia-se o dado do utilizador outravez para guardar se dermos refresh-->
            <form method="POST">
                <input type="hidden" name="edit_uti" value="<?php echo $user_id; ?>">
                <input type="number" name="number" step="0.01" placeholder="0.00" required>
                <br><br>
                <input type="submit" name="minus" value="Retirar">
                <input type="submit" name="add" value="Adicionar">
            </form>
            <button class="btn" onclick="window.location.href='gestao_utili.php'">Voltar ao Perfil</button>
        </div>
    </div>
</body>

</html>