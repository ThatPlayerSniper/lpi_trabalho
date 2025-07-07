<?php
// Inclui o ficheiro de ligação à base de dados
require_once "../basedados/basedados.h";

define('INCLUDE_CHECK', true);

// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já têm uma sessão iniciada caso não tenha cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o utilizador é admin ou funcionário, caso contrário redireciona para o index
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
    <!-- Fonte personalizada do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
    <!-- Folha de estilos para o perfil -->
    <link rel="stylesheet" href="perfil.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
?>

<body>
    <div class="background">
        <div class="card">
            <?php

            $user_id = '';

            // Verifica se recebe um novo utilizador via GET (vindo da página gestao_utili.php)
            if (isset($_GET['edit_uti']) && !empty($_GET['edit_uti'])) {
                $user_id = escapeString($_GET['edit_uti']);
                $_SESSION['current_edit_user'] = $user_id; // Atualiza a sessão com o novo utilizador
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
                // Sanitiza os dados recebidos do formulário
                $nome = isset($_POST['nome']) ? escapeString($_POST['nome']) : '';
                $endereco = isset($_POST['endereco']) ? escapeString($_POST['endereco']) : '';
                $pass = isset($_POST['pass']) ? escapeString($_POST['pass']) : '';
                $cargo = isset($_POST['cargoMudar']) ? escapeString($_POST['cargoMudar']) : '';

                // Verifica se todos os campos obrigatórios estão preenchidos
                if (!empty($nome) && !empty($endereco) && !empty($user_id) && !empty($pass) && !empty($cargo)) {
                    $passHash = hash('sha256', $pass);

                    // Atualiza os dados do utilizador na base de dados
                    $sql = "UPDATE utilizador SET nome = '$nome', endereco = '$endereco', secretpass = '$passHash', cargo = '$cargo' WHERE id_utilizador = '$user_id'";
                    executarQuery($sql);
                    echo "<p>Perfil atualizado com sucesso!</p>";
                } else {
                    echo "<p>Têm de preencher todos os campos</p>";
                }
            }

            // Processamento das transações financeiras (adicionar ou retirar saldo)
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['minus']) || isset($_POST['add']))) {
                $valor = isset($_POST['number']) ? floatval($_POST['number']) : 0.00;
                $minus = isset($_POST['minus']) ? true : false;
                $add = isset($_POST['add']) ? true : false;

                // Inner join para obter os dados do utilizador e carteira
                $sql = "SELECT u.*, c.saldo_atual 
                    FROM utilizador u
                    INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                    WHERE u.id_utilizador = '$user_id'";

                // Executa a query para obter os dados do utilizador
                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $valor_car = floatval($row['saldo_atual']);

                    // Se for para retirar saldo
                    if ($minus) {
                        if ($valor <= $valor_car) {
                            $valorRetirar = $valor;
                            $novoSaldo = $valor_car - $valorRetirar;

                            // Atualiza o saldo da carteira do utilizador
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = '$user_id'";
                            executarQuery($sql2);

                            // Regista a transação de levantamento
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                                VALUES (" . (int)$row['id_utilizador'] . ", 'levantamento', " . floatval($valorRetirar) . ", $novoSaldo)";
                            executarQuery($sqlInsert);

                            // Verifica se o utilizador Felix (id=1) existe para atualizar a sua carteira
                            $sql = "SELECT u.*, c.saldo_atual 
                                FROM utilizador u
                                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                                WHERE u.id_utilizador = '1'";
                            $resultadoFelix = executarQuery($sql);

                            if ($resultadoFelix && $resultadoFelix->num_rows > 0) {
                                $rowFelix = $resultadoFelix->fetch_assoc();

                                // Vai buscar o saldo atual da conta Felix e calcula o novo saldo
                                $valorFelix = floatval($rowFelix['saldo_atual']);
                                $novoSaldoFelix = $valorFelix - $valor;

                                // Atualiza o saldo da carteira da conta Felix
                                $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                                executarQuery($sql2);

                                // Regista a transação de reembolso na conta Felix
                                $sql = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                    VALUES (1, 'reembolso', " . floatval($valor) . ", $novoSaldoFelix)";
                                executarQuery($sql);
                            }

                            echo "<p>Valor retirado com sucesso!</p>";
                        } else {
                            // Mensagem de erro caso o saldo seja insuficiente
                            echo "<h2>ERRO: Saldo insuficiente</h2>";
                        }
                    }

                    // Se for para adicionar saldo
                    if ($add) {
                        $valorAdicionar = $valor;
                        $novoSaldo = $valor_car + $valorAdicionar;

                        // Atualiza o saldo da carteira do utilizador
                        $sql5 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = '$user_id'";
                        executarQuery($sql5);

                        // Regista a transação de depósito
                        $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao) 
                            VALUES (" . (int)$row['id_utilizador'] . ", 'deposito', " . floatval($valorAdicionar) . ", $novoSaldo)";
                        executarQuery($sqlInsert);

                        // Verifica se o utilizador Felix (id=1) existe para atualizar a sua carteira
                        $sql = "SELECT u.*, c.saldo_atual 
                            FROM utilizador u
                            INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                            WHERE u.id_utilizador = '1'";
                        $resultadoFelix = executarQuery($sql);

                        // Se a conta Felix existir, atualiza o saldo e regista a transação
                        if ($resultadoFelix && $resultadoFelix->num_rows > 0) {
                            $rowFelix = $resultadoFelix->fetch_assoc();

                            // Vai buscar o saldo atual da conta Felix e calcula o novo saldo
                            $valorFelix = floatval($rowFelix['saldo_atual']);
                            $novoSaldoFelix = $valorFelix + $valor;

                            // Atualiza o saldo da carteira da conta Felix
                            $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldoFelix' WHERE id_utilizador = 1";
                            executarQuery($sql2);

                            // Regista a transação de transferência na conta Felix
                            $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                VALUES (1, 'transferencia', " . floatval($valor) . ", $novoSaldoFelix)";
                            executarQuery($sqlInsert);
                        }

                        echo "<p>Valor adicionado com sucesso!</p>";
                    }
                }
            }
            ?>

            <!-- Formulário para editar dados do utilizador -->
            <form method="post" class="header">
                <input type="hidden" name="edit_uti" value="<?php echo $user_id; ?>">
                <?php
                // Select para obter os dados do utilizador e carteira
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
                            <!-- Apenas mostra o nome para funcionários -->
                            <h2>Conta: <span><?= htmlspecialchars($row['nome']) ?></span></h2>
                        <?php endif; ?>
                        <?php if (seForAdminNR()) : ?>
                            <!-- Campos de edição para administradores -->
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
                        // Mensagem caso o utilizador não seja encontrado
                        echo "<h3>O utilizador não foi encontrado!</h3>";
                    }
                } else {
                    // Mensagem caso nenhum utilizador esteja selecionado
                    echo "<h3>Nenhum utilizador selecionado!</h3>";
                }
                ?>
            </form>
            <br>

            <?php

            // Mostra o saldo do utilizador
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
            <!-- Formulário para adicionar ou retirar saldo ao utilizador -->
            <!-- Envia-se o dado do utilizador outra vez para guardar se dermos refresh -->
            <form method="POST">
                <input type="hidden" name="edit_uti" value="<?php echo $user_id; ?>">
                <input type="number" name="number" step="0.01" placeholder="0.00" required>
                <br><br>
                <input type="submit" name="minus" value="Retirar">
                <input type="submit" name="add" value="Adicionar">
            </form>
            <!-- Botão para voltar à página de gestão de utilizadores -->
            <button class="btn" onclick="window.location.href='gestao_utili.php'">Voltar ao Perfil</button>
        </div>
    </div>
</body>

</html>