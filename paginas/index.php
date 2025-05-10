    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="index.css">
        <title>Document</title>

    </head>

    <?php
    require_once "./nav.php";
    ?>

    <body>

    <?php
        if (seNaoAdminNR() == true) {
            echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
        }
    ?>

    </body>

    <?php
        $sql ="SELECT tipo_alerta, descricao, data_expira FROM alertas 
                WHERE data_expira >= CURDATE() 
                ORDER BY data_expira ASC LIMIT 3";
        $resultado = executarQuery($sql);
        
    ?>

    <div class="container">
    <div class="right-container">
        <div>
            <label>
                <h1>ALERTAS</h1>
            </label>
        </div>
        <div class="note-card">
        <?php while ($alerta = $resultado->fetch_assoc()) {?>
        <div class="note-header"> <?php echo $alerta['tipo_alerta']; ?> </div>
        <div class="note-body">
            <p><?php echo $alerta['descricao']; ?></p>
        </div>
        <div class="note-footer">
            <span><?php echo "Termina em ".$alerta['data_expira']; ?></span>
            
        </div>
        <br>
        <?php } ?>
    
        <?php
        if (seNaoAdminNR() == true) {
            echo "<button onclick=\"window.location.href='alertas.php'\">Lista de Alertas</button>";
        }
    ?>

    </div>
    </div>
    <div class="container">
            <div class="left-container">
                    
            </div>
    </div>
 

    </html>