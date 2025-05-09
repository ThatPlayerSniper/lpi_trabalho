<?php
require_once "../basedados/basedados.h";
require_once "./auth.php";
//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


//Nós só quero saber se ele é algo para além de estes dois e se 
//os dois falharem sabemos que não têm permissões 
//Retorno nulo como um safeback só para poder fazer
//a verificação ( o null não faz nada)
if (seNaoAdmin() && seNaoFun()) {
    return null;
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<?php
require_once "./nav.php";
?>

<body>
    <div class="big-box">
        <br><br>
        <?php
        $sql = "SELECT * FROM utilizador WHERE estado_conta = 'registado' AND cargo = 'cliente'";
        $resultado = executarQuery($sql);

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo '<li">
                    <div>
                        ' . $row["nome"] . ' - ' . $row["endereco"] . ' <br>estado da conta: ' . $row["estado_conta"] . ' <br>cargo: ' . $row["cargo"] . ' |  saldo: ' . $row["saldo"] . '
                   </div>
                    <div>
                        <form method="post" action="visualizacao_perfil.php">
                            <button type="submit" name="vis_userID" value="' . $row["id_utilizador"] . '">visualizar perfil</button>
                        </form>
                    </div>
                </li><br>';
            }
        }

        ?>
    </div>
</body>

</html>