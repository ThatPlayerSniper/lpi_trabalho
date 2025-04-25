
<?php
define('server', 'localhost'); // meu computador
define('user', 'root'); // user
define('password', ''); //CASO ERRO mudar palavra pass
define('database','felixbusdb'); //banco de dados

$database = 'felixbusdb';
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';

$conn = new mysqli( server,user,password,database);


//Verificação se ouver um erro na conexão
if($conn->connect_error){
    die("Erro na conexão: " . $conn->connect_error);
}

//Serve para "sanatizar os inputs e prevenir sql ejection"

function escapeString($variable){
    
    global $conn;

    $variable = $conn -> real_escape_string($variable);
    return $variable;
    
    /*Nota: Para evitar complexidade usamos o escapeString,
    não é tão bom como o prepared statements mas é seguro o suficiente para 
    este trabalho
*/
}

//Executar o processor do sql/ chamar está função sempre que for necessário executar alguma coisa   

function executarQuery($sql)
{
    global $conn;

    $resultado = $conn->query($sql);

    if (!$resultado) {
        echo "Erro na execução da query: " . $conn->error;
        exit;
    }
    return $resultado;

    /*Problemas ao perceber o porque dos erros co a basedados.h

    */
}

?>