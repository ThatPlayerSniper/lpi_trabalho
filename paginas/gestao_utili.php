<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

seNaoAdmin();

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

    //MARIANA --TODO 

    if (isset($_POST["aprovar"])) {
        $sql="UPDATE TABLE utilizador SET estado_conta = registado WHERE id_utilizador='$id";
        $resultado = executarQuery($sql);
    }

    if (isset($_POST["rejeitar"])) {
        $sql="UPDATE TABLE utilizador SET estado_conta = rejeitado WHERE id_utilizador='$id";
        $resultado = executarQuery($sql);
    }
    ?>


    <div class="big-box">
        <div>
            
            <form method="POST"><br><br>
                <select name="estado" onchange="this.form.submit()">
                    <option ""> --Filtrar por estado --</option>
                    <option value="pendente">Pendente</option>
                    <option value="registado">registado</option>
                    <option value="rejeitado">rejeitado</option>
                </select>
            </form><br><br>
            <?php

            //Vai buscar os dados do form (trenario)
            $estado = isset($_POST['estado']) ? $_POST['estado'] : '';

            //Verificão para o filtro
            //NO?
            if (!empty($estado)) {
                $estado = escapeString($estado);    //Limpa o input e envio para um comando
                $sql = "SELECT * FROM utilizador WHERE estado_conta = '$estado'";
            }
            //YES?
            else {
                $sql = "SELECT * FROM utilizador";
            }

            $resultado = executarQuery($sql);
            if ($resultado->num_rows > 0) {

                while ($row = $resultado->fetch_assoc())
                    echo '<li">
                    <div>
                        ' . $row["nome"] . ' - ' . $row["endereco"] . '
                            </div>
                                <div>
                            <form method="post">
                                <button type="submit" value="' . $row["id_utilizador"] . '" name="aprovar" class="approve">Aprovar</button>
                                <button type="submit" value="' . $row["id_utilizador"] . '" name="rejeitar" class="deny">Rejeitar</button>
                            </form>
                        </div>
                    </li><br>';
            } else {
                echo "<p>Não há registos</p>";
            }

            ?>
        </div>
    </div>
</body>

</html>