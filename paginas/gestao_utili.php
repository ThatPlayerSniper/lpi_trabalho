<?php
// Inclui o ficheiro de ligação à base de dados
require_once '../basedados/basedados.h';
// Inclui o ficheiro de autenticação
require_once "./auth.php";
// Verifica se já têm uma sessão iniciada, caso não tenha cria uma
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
    <!-- Ficheiro de estilos CSS -->
    <link rel="stylesheet" href="gestaoUti.css">
</head>
<?php
// Inclui a barra de navegação
require_once "./nav.php";
?>

<body>
    <?php

    // Se o botão "aprovar" for pressionado, atualiza o estado da conta para 'registado'
    if (isset($_POST["aprovar"])) {
        $id = intval($_POST['aprovar']);
        $sql = "UPDATE utilizador SET estado_conta = 'registado' WHERE id_utilizador='$id'";
        $resultado = executarQuery($sql);
    }

    // Se o botão "rejeitar" for pressionado, atualiza o estado da conta para 'rejeitado'
    if (isset($_POST["rejeitar"])) {
        $id = intval($_POST['rejeitar']);
        $sql = "UPDATE utilizador SET estado_conta = 'rejeitado' WHERE id_utilizador = '$id'";
        $resultado = executarQuery($sql);
    }

    ?>
    <div class="big-box">
        <div>
            <div><br><br>
                <!-- Formulário de pesquisa por cargo e estado -->
                <form method="POST">
                    <div>
                        <label>Cargo:</label>
                        <select name="cargo">
                            <option value="">-- Seleciona um --</option>
                            <option value="cliente">Cliente</option>
                            <option value="funcionario">Funcionario</option>
                            <option value="admin">Admin</option>
                        </select>
                        <label>Estado:</label>
                        <select name="estado">
                            <option value="">-- Seleciona um --</option>
                            <option value="pendente">Pendente</option>
                            <option value="registado">Registado</option>
                            <option value="rejeitado">Rejeitado</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
        <table>
            <tr>
                <?php
                // Vai buscar os dados do form (operador ternário)
                $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
                $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : '';

                // Verificação para o filtro de pesquisa
                if (!empty($estado) && !empty($cargo)) {
                    // Ambos filtros selecionados
                    $estado = escapeString($estado);
                    $cargo = escapeString($cargo);
                    $sql = "SELECT * FROM utilizador WHERE estado_conta = '$estado' AND cargo = '$cargo'";
                } elseif (!empty($estado)) {
                    // Apenas estado selecionado
                    $estado = escapeString($estado);
                    $sql = "SELECT * FROM utilizador WHERE estado_conta = '$estado'";
                } elseif (!empty($cargo)) {
                    // Apenas cargo selecionado
                    $cargo = escapeString($cargo);
                    $sql = "SELECT * FROM utilizador WHERE cargo = '$cargo'";
                } else {
                    // Nenhum filtro, mostrar todos os utilizadores
                    $sql = "SELECT * FROM utilizador";
                }

                // Executa a query e obtém o resultado
                $resultado = executarQuery($sql);

                // Verifica se existem resultados
                if ($resultado && $resultado->num_rows > 0) {
                    // Percorre todos os utilizadores encontrados
                    while ($row = $resultado->fetch_assoc()) {
                ?>
                        <br><br>
                        <li>
                            <div>
                                <!-- Mostra o nome e endereço do utilizador -->
                                <?php echo $row["nome"]; ?> - <?php echo $row["endereco"]; ?>
                                <br>estado da conta: <?php echo $row["estado_conta"]; ?>
                                <br>cargo: <?php echo $row["cargo"]; ?>
                            </div>
                            <div>
                                <?php if (seForAdminNR() == true) { ?>
                                    <!-- Formulário para aprovar ou rejeitar registo (apenas para admin) -->
                                    <form method="post">
                                        <button type="submit" value="<?php echo $row["id_utilizador"]; ?>" name="aprovar" class="approve">Aprovar Registo</button>
                                        <button type="submit" value="<?php echo $row["id_utilizador"]; ?>" name="rejeitar" class="deny">Rejeitar Registo</button>
                                    </form>
                                <?php } ?>
                                <!-- Formulário para visualizar perfil do utilizador -->
                                <form method="post" action="visualizacao_perfil.php">
                                    <button type="submit" name="vis_userID" value="<?php echo $row["id_utilizador"]; ?>">visualizar perfil</button>
                                </form>
                                <!-- Formulário para editar perfil do utilizador -->
                                <form method="GET" action="editar_visu.php">
                                    <input type="hidden" name="edit_uti" value="<?= htmlspecialchars($row['id_utilizador']) ?>">
                                    <button type="submit">editar perfil</button>
                                </form>
                            </div>
                        </li>
                <?php
                    }
                } else {
                    // Caso não existam registos
                    echo "<p>Não há registos</p>";
                }
                ?>
            </tr>
        </table>
    </div>
    </div>
</body>

</html>