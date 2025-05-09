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
        $sql ="SELECT tipo_alerta FROM alertas ORDER BY data_expira ASC";
        $tipo = executarQuery($sql);
        $tipo = $tipo->fetch_assoc();

        $sql ="SELECT  descricao FROM alertas ORDER BY data_expira ASC";
        $descricao = executarQuery($sql);
        $descricao = $descricao->fetch_assoc();

        $sql ="SELECT data_expira FROM alertas ORDER BY data_expira ASC";
        $validade = executarQuery($sql);
        $validade = $validade->fetch_assoc();

    ?>

    <div class="note-card">
        <div class="note-header"> <?php echo htmlspecialchars($tipo['tipo_alerta']); ?> </div>
        <div class="note-body">
            <p><?php echo htmlspecialchars($descricao['descricao']); ?></p>
        </div>
        <div class="note-footer">
            <span><?php echo "Termina em ".htmlspecialchars($validade['data_expira']); ?></span>
        </div>
    </div>

 

    </html>