<?php

//AUTH FILE: Simplesmente para facilitar algumas tarefas e evitar repetir código e só chamar o que é preciso para executar 
//NOTA: Caso haja erros no vs (VISUAL STUDIO) olhar duas vezes, o php pode estar mal configurado (nota mental)

require_once "../basedados/basedados.h";

//Verifica se já têm uma sessão iniciada caso não tenho cria uma
//Proteção de TROUBLESHOOTING
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


/*--------------------------------------*/

//Verificação se ele recebe um id
function Logged()
{
    return isset($_SESSION["user_id"]);
}

//Ver cargo para distribuir permissão
function VerificarCargo()
{

    //Se não tiver id, automaticamente passa a visitante na sessão
    if (!Logged()) {
        return "visitante";
    }

    if (logged()) {
        if (isset($_SESSION["cargo"])) {
            return $_SESSION["cargo"];
        } else {
            logout();
        }
    } else {
        logout();
    }
}

/**
 * Verifcações de restrição
 * Chamar estes metedos caso for precisar restringir acesso
 * @return void
 */
function seNaoAdmin()
{
    if ($_SESSION["cargo"] != "admin") {
        header("location: index.php");
        exit();
    }
}

function seNaoAdminNR() {
    if (VerificarCargo() != "admin") {
        return false;
    }else{
        return true;
    }
}

function seNaoFun()
{
    if ($_SESSION["cargo"] != "funcionario") {
        header("location: index.php");
        exit();
    }
}
function seNaoClie()
{
    if ($_SESSION["cargo"] != "cliente") {
        header("location: index.php");
        exit();
    }
}

/**
 * Sumario do login
 * @param mixed $nome
 * @param mixed $endereco
 * @param mixed $pass
 * @return bool
 */

function login($nome, $endereco, $pass)
{

    //escapeString para adicionar seguraça contra sql ejection
    /*
        Oque faz é elemina alguns caracteres, de forma a quando estes dados passarem a
        base de dados o sql(que é burro) não interpretar como comando e sim com um dado e
        processalo como tal
    */

    $nome = escapeString($nome);
    $endereco = escapeString($endereco);
    $pass = escapeString($pass);
    $estado = "registado";

    // Busca o utilizador no banco de dados
    $sql = "SELECT * FROM utilizador
    WHERE nome = '$nome' 
    AND endereco = '$endereco' 
    AND estado_conta = '$estado'";

    //executa
    $resultado = executarQuery($sql);

    //Adicionado para debug!!
    //print_r ($resultado); 


    //Verificar se o utilizador foi encontrado
    if ($resultado && $resultado->num_rows >= 1) {
        $utilizador = $resultado->fetch_assoc();

        //Debbug para ver o utilizador (dados)
        //print_r($utilizador);


        // Verifica se a senha está correta
        if (isset($utilizador['secretpass']) && $pass == $utilizador['secretpass']) {
            // Suceso
            $_SESSION['user_id'] = $utilizador['id_utilizador'];
            $_SESSION['cargo'] = $utilizador['cargo'];
            //print_r($utilizador);
            return true;
        }
        //se ele não estiver registado erro
        if ($estado != "registado") {
            echo '<div class="input-group">
                <label>Este utilizador não está registado</label>
            </div>';
        }
    }
    return false;
}



function registarUti($nome, $endereco, $secretpass)
{

    $nome = escapeString($nome);
    $endereco = escapeString($endereco);
    $secretpass = escapeString($secretpass);

    $sql = "SELECT * FROM utilizador WHERE nome = '$nome' 
            AND endereco = '$endereco' 
            AND secretpass = '$secretpass'";
    $resultado = executarQuery($sql);

    if ($result->num_rows > 0) {
        header("Location: registar.php");
        exit;
    } else {

        // Criar a query SQL para inserir o novo utilizador
        $sql = "INSERT INTO utilizador (nome, endereco, secretpass )
                VALUES ('$nome', '$endereco', '$secretpass' )";

        //Executa a Query
        $sql = "INSERT INTO utilizador (nome, endereco, secretpass)
                VALUES ('$nome', '$endereco', '$secretpass')";

        $resultado = executarQuery($sql);


    }



    //Verifica se conseguiu fazer o insert
    if ($resultado) {
        return true; //Sucesso
    } else {
        return false; // Caso Erro
    }
}

//Logout (mata a sessão)
function logout()
{
    session_unset(); //Destroi todos a várias
    session_destroy(); //Destroi a sessão (dead)
    header("location:index.php"); //E para terminar manda-o de volta a página principal
}


function getUser()
{
    $result = executarQuery("SELECT * from utilizador WHERE id_utilizador = " . $_SESSION['user_id']);
    return $result->fetch_assoc();
}