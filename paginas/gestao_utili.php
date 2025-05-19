<?php
require_once '../basedados/basedados.h';
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
    <link rel="stylesheet" href="gestaoUti.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
    <?php

    if (isset($_POST["aprovar"])) {
        $id = intval($_POST['aprovar']);
        $sql = "UPDATE utilizador SET estado_conta = 'registado' WHERE id_utilizador='$id'";
        $resultado = executarQuery($sql);
    }

    if (isset($_POST["rejeitar"])) {
        $id = intval($_POST['rejeitar']);
        $sql = "UPDATE utilizador SET estado_conta = 'rejeitado' WHERE id_utilizador = '$id'";
        $resultado = executarQuery($sql);
    }

    ?>
    <div class="big-box">
        <div>
            <div><br><br>
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
                //Vai buscar os dados do form (trenario)
                $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
                $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : '';

                //Verificão para o filtro
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
                    // Nenhum filtro, mostrar todos
                    $sql = "SELECT * FROM utilizador";
                }

                $resultado = executarQuery($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                ?>
                        <br><br>
                        <li>
                            <div>
                                <?php echo $row["nome"]; ?> - <?php echo $row["endereco"]; ?>
                                <br>estado da conta: <?php echo $row["estado_conta"]; ?>
                                <br>cargo: <?php echo $row["cargo"]; ?>
                            </div>
                            <div>
                                <form method="post">
                                    <button type="submit" value="<?php echo $row["id_utilizador"]; ?>" name="aprovar" class="approve">Aprovar Registo</button>
                                    <button type="submit" value="<?php echo $row["id_utilizador"]; ?>" name="rejeitar" class="deny">Rejeitar Registo</button>
                                </form>
                                <form method="post" action="visualizacao_perfil.php">
                                    <button type="submit" name="vis_userID" value="<?php echo $row["id_utilizador"]; ?>">visualizar perfil</button>
                                </form>
                                <form method="GET" action="editar_visu.php">
                                    <input type="hidden" name="edit_uti" value="<?= htmlspecialchars($row['id_utilizador']) ?>">
                                    <button type="submit">editar perfil</button>
                                </form>
                            </div>
                        </li>
                <?php
                    }
                } else {
                    echo "<p>Não há registos</p>";
                }
                ?>
            </tr>
        </table>
    </div>
    </div>
</body>

</html>