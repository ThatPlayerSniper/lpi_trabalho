<?php
// Incluir ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';

// Verifica se o ficheiro de autenticação já foi incluído
define('INCLUDE_CHECK', true);

// Incluir ficheiro de autenticação
require_once "./auth.php";

// Verificar se a sessão já foi iniciada, se não, iniciar
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o método de requisição é POST e se o botão 'comprar' foi pressionado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comprar'])) {
    // Obter o id da viagem e da rota do POST
    $id_viagem = isset($_POST['id_viagem']) ? $_POST['id_viagem'] : '';
    $id_rota = isset($_POST['rota']) ? $_POST['rota'] : '';

    // Verificar se o ID da viagem e da rota são válidos (escapar strings)
    $id_viagem = escapeString($id_viagem);
    $id_rota = escapeString($id_rota);

    // Se o id da viagem estiver vazio, redirecionar com erro
    if (empty($id_viagem)) {
        header("Location: viagem.php?rota=$id_rota&error=Viagem inválida");
        exit();
    }

    // Verificar se o utilizador está autenticado
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?error=Por favor faça login primeiro");
        exit();
    }

    // Verificar se ainda há lugares disponíveis na viagem (lock de registo com FOR UPDATE)
    $sql_verificar = "SELECT v.*, vt.capacidade_lugares 
                      FROM viagem v 
                      JOIN viatura vt ON v.id_viatura = vt.id_viatura 
                      WHERE v.id_viagem = '$id_viagem' FOR UPDATE"; // FOR UPDATE para lockar o registro

    $result_verificar = executarQuery($sql_verificar);

    // Se encontrou a viagem
    if ($result_verificar && $result_verificar->num_rows > 0) {
        $viagem_info = $result_verificar->fetch_assoc();

        // Se não há lugares disponíveis, redirecionar com erro
        if ($viagem_info['lugares_ocupados'] >= $viagem_info['capacidade_lugares']) {
            header("Location: viagem.php?rota=$id_rota&error=Esta viagem já não tem lugares disponíveis");
            exit();
        }

        // Se o utilizador for admin ou funcionário
        if ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'funcionario') {
            // Obter id do utilizador selecionado no POST
            $id_utilizador = isset($_POST['id_utilizador']) ? $_POST['id_utilizador'] : '';

            // Escapar o id do utilizador
            $id_utilizador = escapeString($id_utilizador);
            // Se não foi selecionado nenhum utilizador, redirecionar com erro
            if (empty($id_utilizador)) {
                header("Location: viagem.php?rota=$id_rota&error=Selecione um utilizador");
                exit();
            }

            // Buscar dados do utilizador e saldo
            $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = '$id_utilizador'";

            $resultado = executarQuery($sql);

            // Se encontrou o utilizador
            if ($resultado && $resultado->num_rows > 0) {
                $row4 = $resultado->fetch_assoc();
                $valor_car = floatval($row4['saldo_atual']);
                $valor = floatval($viagem_info['preco']);

                // Verificar se o utilizador tem saldo suficiente
                if ($valor_car < $valor) {
                    header("Location: viagem.php?rota=$id_rota&error=Saldo insuficiente. Saldo atual: €" . number_format($valor_car, 2));
                    exit();
                }

                // Iniciar transação na base de dados
                executarQuery("START TRANSACTION");

                try {
                    // Atualizar saldo do utilizador
                    $novoSaldo = $valor_car - $valor;
                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$row4['id_utilizador'];
                    executarQuery($sql2);

                    // Registar transação do utilizador
                    $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (" . (int)$row4['id_utilizador'] . ", 'compra_bilhete', " . floatval($valor) . ", $novoSaldo)";
                    executarQuery($sqlInsert);

                    // Atualizar saldo da empresa (Felix - id = 1)
                    $sqlFelix = "UPDATE carteira SET saldo_atual = saldo_atual + $valor WHERE id_utilizador = 1";
                    executarQuery($sqlFelix);

                    // Registar transação da empresa
                    $sqlFelixTrans = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                  VALUES (1, 'transferencia', " . floatval($valor) . ", 
                                  (SELECT saldo_atual FROM carteira WHERE id_utilizador = 1))";
                    executarQuery($sqlFelixTrans);

                    // Atualizar número de lugares ocupados na viagem
                    $sqlUpdateViagem = "UPDATE viagem SET lugares_ocupados = lugares_ocupados + 1 WHERE id_viagem = '$id_viagem'";
                    executarQuery($sqlUpdateViagem);

                    // Obter dados do utilizador autenticado para o bilhete
                    $utilizador = getUser();

                    // Criar registo do bilhete
                    $sqlCreate = "INSERT INTO bilhete (id_viagem, id_utilizador, estado_bilhete, nome_cliente, id_rota)
                             VALUES ('$id_viagem', '" . (int)$utilizador['id_utilizador'] . "', 'ativo', 
                             '" . htmlspecialchars($utilizador['nome']) . "', '$id_rota')";
                    $resultCreate = executarQuery($sqlCreate);

                    // Se tudo correu bem, commit e redirecionar com sucesso
                    if ($resultCreate) {
                        executarQuery("COMMIT");
                        header("Location: viagem.php?rota=$id_rota&success=Bilhete comprado com sucesso!");
                        exit();
                    } else {
                        // Se falhou ao criar bilhete, rollback e redirecionar com erro
                        executarQuery("ROLLBACK");
                        header("Location: viagem.php?rota=$id_rota&error=Erro ao criar bilhete");
                        exit();
                    }
                } catch (Exception $e) {
                    // Em caso de exceção, rollback e redirecionar com erro
                    executarQuery("ROLLBACK");
                    header("Location: viagem.php?rota=$id_rota&error=Ocorreu um erro durante a compra");
                    exit();
                }
            } else {
                // Se não encontrou o utilizador, redirecionar com erro
                header("Location: viagem.php?rota=$id_rota&error=Erro ao obter dados do utilizador");
                exit();
            }
        }else if ($_SESSION['cargo'] == 'cliente') {
            // Se o utilizador for cliente, buscar os seus dados e saldo
            $sql = "SELECT u.*, c.saldo_atual 
                FROM utilizador u
                INNER JOIN carteira c ON u.id_utilizador = c.id_utilizador
                WHERE u.id_utilizador = " . (int)$_SESSION['user_id'] . " FOR UPDATE";

            $resultado = executarQuery($sql);

            // Se encontrou o utilizador
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
                    // Atualizar saldo do utilizador
                    $novoSaldo = $valor_car - $valor;
                    $sql2 = "UPDATE carteira SET saldo_atual = '$novoSaldo' WHERE id_utilizador = " . (int)$row4['id_utilizador'];
                    executarQuery($sql2);

                    // Registar transação do utilizador
                    $sqlInsert = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                            VALUES (" . (int)$row4['id_utilizador'] . ", 'compra_bilhete', " . floatval($valor) . ", $novoSaldo)";
                    executarQuery($sqlInsert);

                    // Atualizar saldo da empresa (Felix - id = 1)
                    $sqlFelix = "UPDATE carteira SET saldo_atual = saldo_atual + $valor WHERE id_utilizador = 1";
                    executarQuery($sqlFelix);

                    // Registar transação da empresa
                    $sqlFelixTrans = "INSERT INTO transacoes (id_utilizador, tipo_transacao, valor, saldo_apos_transacao)
                                  VALUES (1, 'transferencia', " . floatval($valor) . ", 
                                  (SELECT saldo_atual FROM carteira WHERE id_utilizador = 1))";
                    executarQuery($sqlFelixTrans);

                    // Atualizar número de lugares ocupados na viagem
                    $sqlUpdateViagem = "UPDATE viagem SET lugares_ocupados = lugares_ocupados + 1 WHERE id_viagem = '$id_viagem'";
                    executarQuery($sqlUpdateViagem);

                    // Obter dados do utilizador autenticado para o bilhete
                    $utilizador = getUser();

                    // Criar registo do bilhete
                    $sqlCreate = "INSERT INTO bilhete (id_viagem, id_utilizador, estado_bilhete, nome_cliente, id_rota)
                             VALUES ('$id_viagem', '" . (int)$utilizador['id_utilizador'] . "', 'ativo', 
                             '" . htmlspecialchars($utilizador['nome']) . "', '$id_rota')";
                    $resultCreate = executarQuery($sqlCreate);

                    // Se tudo correu bem, commit e redirecionar com sucesso
                    if ($resultCreate) {
                        executarQuery("COMMIT");
                        header("Location: viagem.php?rota=$id_rota&success=Bilhete comprado com sucesso!");
                        exit();
                    } else {
                        // Se falhou ao criar bilhete, rollback e redirecionar com erro
                        executarQuery("ROLLBACK");
                        header("Location: viagem.php?rota=$id_rota&error=Erro ao criar bilhete");
                        exit();
                    }
                } catch (Exception $e) {
                    // Em caso de exceção, rollback e redirecionar com erro
                    executarQuery("ROLLBACK");
                    header("Location: viagem.php?rota=$id_rota&error=Ocorreu um erro durante a compra");
                    exit();
                }
            } else {
                // Se não encontrou o utilizador, redirecionar com erro
                header("Location: viagem.php?rota=$id_rota&error=Erro ao obter dados do utilizador");
                exit();
            }
        } else {
            // Se o tipo de utilizador não for permitido, redirecionar com erro
            header("Location: viagem.php?rota=$id_rota&error=Tipo de utilizador não autorizado");
            exit();
        }
    }
} else {
    // Se o método não for POST ou faltar parâmetros, redirecionar com erro
    header("Location: perfil.php?error!=Método de requisição inválido ou falta de parâmetros");
    exit();
}
