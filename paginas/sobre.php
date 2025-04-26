<?php
require_once '../basedados/basedados.h';
require_once './auth.php';

//Verifica se já têm uma sessão iniciada caso não tenho cria uma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felix Bus</title>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:wght@100..900&display=swap" rel="stylesheet">
</head>
<?php
require_once "./nav.php";
?>


<body>
    <div class="container">
        <div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3064.44732917791!2d-7.509744110989378!3d39.81938723174352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd3d5ea6bb2280e1%3A0x1c460157bc4b46c8!2sEscola%20Superior%20de%20Tecnologia%20-%20Instituto%20Polit%C3%A9cnico%20de%20Castelo%20Branco!5e0!3m2!1spt-PT!2spt!4v1745551591652!5m2!1spt-PT!2spt" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="contact-section">
            <h1>Contactos</h1>
            <div class="contact-box">
            <span>
                Ligue  +351 272339300<br>
              </span>
            <span>
                <br>Contacte-nos em felixbus@ipcb.pt
              </span>
            </div>
        </div>
    </div>
</body>


</html>

