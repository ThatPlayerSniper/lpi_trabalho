<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comprar'])) {
    $id_viagem = isset($_POST['id_viagem']) ? $_POST['id_viagem'] : '';
    $id_rota = isset($_POST['rota']) ? $_POST['rota'] : '';

    // Verificar se o ID da viagem e da rota são válidos
    $id_viagem = escapeString($id_viagem);
    $id_rota = escapeString($id_rota);

    if (empty($id_viagem)) {
        header("Location: viagem.php?rota=$id_rota&error=Viagem inválida");
        exit();
    }

    // Verificar se o usuário está logado
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?error=Por favor faça login primeiro");
        exit();
    }

    // Verificar se ainda há lugares disponíveis
    $sql_verificar = "SELECT v.*, vt.capacidade_lugares 
                      FROM viagem v 
                      JOIN viatura vt ON v.id_viatura = vt.id_viatura 
                      WHERE v.id_viagem = '$id_viagem' FOR UPDATE"; // FOR UPDATE para lockar o registro

    $result_verificar = executarQuery($sql_verificar);

    if ($result_verificar && $result_verificar->num_rows > 0) {
        $viagem_info = $result_verificar->fetch_assoc();

        if ($viagem_info['lugares_ocupados'] >= $viagem_info['capacidade_lugares']) {
            header("Location: viagem.php?rota=$id_rota&error=Esta viagem já não tem lugares disponíveis");
            exit();
        }

        if ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'funcionario') {
            $id_utilizador = isset($_POST['id_utilizador']) ? $_POST['id_utilizador'] : '';

            $id_utilizador = escapeString($id_utilizador);
            if (empty($id_utilizador)) {
                header("Location: viagem.php?rota=$id_rota&error=Selecione um utilizador");
                exit();
            }

            $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = '$id_utilizador'";

            $resultado = executarQuery($sql);

            if ($resultado && $resultado->num_rows > 0) {
                $row4 = $resultado->fetch_assoc();
                $valor_car = floatval($row4['saldo_atual']);
                $valor = floatval($viagem_info['preco']);

                // Verificar se tem saldo suficiente
                if ($valor_car < $valor) {
                    header("Location: viagem.php?rota=$id_rota&error=Saldo insuficiente. Saldo atual: €" . number_format($valor_car, 2));
                    exit();
                }


                // Iniciar transação
                executarQuery("START TRANSACTION");

                try {
                    // Atualiza o saldo do utilizador
                    $novoSaldo = $valor_car - $valor;
                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$row4['id_utilizador'];
                    executarQuery($sql2);

                    // Cria o registo da transação
                    $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (" . (int)$row4['id_utilizador'] . ", 'compra_bilhete', " . floatval($valor) . ", $novoSaldo)";
                    executarQuery($sqlInsert);

                    // Atualizar saldo da empresa (Felix - id = 1)
                    $sqlFelix = "UPDATE carteira SET saldo_atual = saldo_atual + $valor WHERE id_utilizador = 1";
                    executarQuery($sqlFelix);

                    // Registrar transação da empresa
                    $sqlFelixTrans = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                  VALUES (1, 'transferencia', " . floatval($valor) . ", 
                                  (SELECT saldo_atual FROM carteira WHERE id_utilizador = 1))";
                    executarQuery($sqlFelixTrans);

                    // Atualizar lugares ocupados
                    $sqlUpdateViagem = "UPDATE viagem SET lugares_ocupados = lugares_ocupados + 1 WHERE id_viagem = '$id_viagem'";
                    executarQuery($sqlUpdateViagem);

                    // Obter dados do usuário para o bilhete
                    $utilizador = getUser();

                    // Criar bilhete
                    $sqlCreate = "INSERT INTO bilhete (id_viagem, id_utilizador, estado_bilhete, nome_cliente, id_rota)
                             VALUES ('$id_viagem', '" . (int)$utilizador['id_utilizador'] . "', 'ativo', 
                             '" . htmlspecialchars($utilizador['nome']) . "', '$id_rota')";
                    $resultCreate = executarQuery($sqlCreate);

                    if ($resultCreate) {
                        executarQuery("COMMIT");
                        header("Location: viagem.php?rota=$id_rota&success=Bilhete comprado com sucesso!");
                        exit();
                    } else {
                        executarQuery("ROLLBACK");
                        header("Location: viagem.php?rota=$id_rota&error=Erro ao criar bilhete");
                        exit();
                    }
                } catch (Exception $e) {
                    executarQuery("ROLLBACK");
                    header("Location: viagem.php?rota=$id_rota&error=Ocorreu um erro durante a compra");
                    exit();
                }
            } else {
                header("Location: viagem.php?rota=$id_rota&error=Erro ao obter dados do utilizador");
                exit();
            }
        } else {
            header("Location: viagem.php?rota=$id_rota&error=Viagem não encontrada");
            exit();
        }




        if ($_SESSION['cargo'] == 'cliente') {
            // Obter dados do utilizador e saldo
            $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = " . (int)$_SESSION['user_id'] . " FOR UPDATE";

            $resultado = executarQuery($sql);

            if ($resultado && $resultado->num_rows > 0) {
                $row4 = $resultado->fetch_assoc();
                $valor_car = floatval($row4['saldo_atual']);
                $valor = floatval($viagem_info['preco']);

                // Verificar se tem saldo suficiente
                if ($valor_car < $valor) {
                    header("Location: viagem.php?rota=$id_rota&error=Saldo insuficiente. Saldo atual: €" . number_format($valor_car, 2));
                    exit();
                }


                // Iniciar transação
                executarQuery("START TRANSACTION");

                try {
                    // Atualiza o saldo do utilizador
                    $novoSaldo = $valor_car - $valor;
                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$row4['id_utilizador'];
                    executarQuery($sql2);

                    // Cria o registo da transação
                    $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (" . (int)$row4['id_utilizador'] . ", 'compra_bilhete', " . floatval($valor) . ", $novoSaldo)";
                    executarQuery($sqlInsert);

                    // Atualizar saldo da empresa (Felix - id = 1)
                    $sqlFelix = "UPDATE carteira SET saldo_atual = saldo_atual + $valor WHERE id_utilizador = 1";
                    executarQuery($sqlFelix);

                    // Registrar transação da empresa
                    $sqlFelixTrans = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                  VALUES (1, 'transferencia', " . floatval($valor) . ", 
                                  (SELECT saldo_atual FROM carteira WHERE id_utilizador = 1))";
                    executarQuery($sqlFelixTrans);

                    // Atualizar lugares ocupados
                    $sqlUpdateViagem = "UPDATE viagem SET lugares_ocupados = lugares_ocupados + 1 WHERE id_viagem = '$id_viagem'";
                    executarQuery($sqlUpdateViagem);

                    // Obter dados do usuário para o bilhete
                    $utilizador = getUser();

                    // Criar bilhete
                    $sqlCreate = "INSERT INTO bilhete (id_viagem, id_utilizador, estado_bilhete, nome_cliente, id_rota)
                             VALUES ('$id_viagem', '" . (int)$utilizador['id_utilizador'] . "', 'ativo', 
                             '" . htmlspecialchars($utilizador['nome']) . "', '$id_rota')";
                    $resultCreate = executarQuery($sqlCreate);

                    if ($resultCreate) {
                        executarQuery("COMMIT");
                        header("Location: viagem.php?rota=$id_rota&success=Bilhete comprado com sucesso!");
                        exit();
                    } else {
                        executarQuery("ROLLBACK");
                        header("Location: viagem.php?rota=$id_rota&error=Erro ao criar bilhete");
                        exit();
                    }
                } catch (Exception $e) {
                    executarQuery("ROLLBACK");
                    header("Location: viagem.php?rota=$id_rota&error=Ocorreu um erro durante a compra");
                    exit();
                }
            } else {
                header("Location: viagem.php?rota=$id_rota&error=Erro ao obter dados do utilizador");
                exit();
            }
        } else {
            header("Location: viagem.php?rota=$id_rota&error=Viagem não encontrada");
            exit();
        }
    }
} else {
    header("Location: perfil.php?error=Acesso inválido");
    exit();
}
