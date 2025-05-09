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
    <div class="col-md-6">
        <div class="card mb-3 shadow-sm">
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
            </div>
        </div>
    </div>
</body>

</html>