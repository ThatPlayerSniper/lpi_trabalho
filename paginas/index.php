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

        <div class="big-box">
            <div class="container">
                <?php
                if (seNaoAdminNR() == true) {
                    echo "<button onclick=\"window.location.href='criaAlerta.php'\">Criação de Alertas</button>";
                }
                ?>
                <div class="right-container">
                    <div>
                        <label>
                            <h1>ALERTAS</h1>
                        </label>
                    </div>
                    <?php
                    $sql = "SELECT * FROM alertas WHERE data_expira >= CURDATE() ORDER BY data_expira ASC LIMIT 3";
                    $resultado = executarQuery($sql);

                    if ($resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                            echo "<div class='small-box'>";
                            echo "<h3 class='turnWhite'>Tipo alerta: " . $row['id_alerta'] . "</h3>";
                            echo "<h3 class='turnWhite'>Tipo do alerta: " . $row['tipo_alerta'] . "</h3>";
                            echo "<h3 class='turnWhite'>Descrição: " . $row['descricao'] . " </h3>";
                            echo "</div>";
                        }
                    }

                    ?>
                <button onclick="window.location.href='.php'"><h3>lista de alertas</h3></button>
                </div>
            </div>
            <div class="container">
                <div class="left-container">
                </div>
            </div>
        </div>
    </body>



    </html>