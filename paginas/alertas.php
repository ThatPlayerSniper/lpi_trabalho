<?php
require_once '../basedados/basedados.h';
require_once "./auth.php";
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
    <link rel="stylesheet" href="index.css">
</head>
<?php
require_once "./nav.php";
?>

<body>
<?php
        $sql ="SELECT * FROM alertas 
                ORDER BY data_expira DESC";
        $resultado = executarQuery($sql);
        
    ?>
    <div class="big-box">
        <div>
                <form method="POST">
                    <div">
                        <label>Tipo:</label>
                        <select name="tipo_alerta">
                            <option value="">-- Seleciona um --</option>
                            <option value="cancelamento">Cancelamento</option>
                            <option value="alteracao_rota">Alteração  de Rotas</option>
                            <option value="manutencao">Manutenção</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>   
                <div>
                <button type="submit">Pesquisar</button>
                </div>
                </form>
         </div>
    </div>
       
        <div class="note-card">
         <?php while ($alerta = $resultado->fetch_assoc()) {?>
            <div class="note-header"> <?php echo $alerta['tipo_alerta']; ?> </div>
            <div class="note-body">
                <p><?php echo $alerta['descricao']; ?></p>
                <p><?php echo "Elaborado pelo utlizador nº".$alerta['id_utilizador']; ?></p>
            </div>
            <div class="note-footer">
                <span><?php echo "Termina em ".$alerta['data_expira']; ?></span>
            </div>
            <?php } ?>
             <?php
        //Vai buscar os dados do form (trenario)
        $tipo = isset($_POST['tipo_alerta']) ? $_POST['tipo_alerta'] : '';

        //Verificão para o filtro
        if (!empty($tipo)) {
            // Ambos filtros selecionados
            $tipo = escapeString($tipo);
                $sql = "SELECT * FROM alertas WHERE tipo_alerta = '$tipo' ";
            } else {
                // Nenhum filtro, mostrar todos
                $sql = "SELECT * FROM alertas";
            }
            $resultado = executarQuery($sql);
            ?>

        </div>
    </div>
</div>
</body>

</html>