<?php

//AUTH FILE: Simplesmente para facilitar algumas tarefas e evitar repetir código e só chamar o que é preciso para executar 
//NOTA: Caso haja erros no vs (VISUAL STUDIO) olhar duas vezes, o php pode estar mal configurado 
require_once "../basedados/basedados.h";

if (!defined('INCLUDE_CHECK')) {
    http_response_code(403);
    header('Location: ../paginas/index.php');
    exit();
}

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
 * Chamar estes metedos/funcoes caso for precisar restringir acesso
 * @return void
 */
function seNaoAdmin()
{
    if ($_SESSION["cargo"] != "admin") {
        header("location: index.php");
        exit();
    }
}

function seForAdminNR()
{
    if (VerificarCargo() != "admin") {
        return false;
    } else {
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

function seForFunNR()
{
    if (VerificarCargo() != "funcionario") {
        return false;
    } else {
        return true;
    }
}

function seNaoClie()
{
    if ($_SESSION["cargo"] != "cliente") {
        header("location: index.php");
        exit();
    }
}

function seForClienteNR()
{
    if (VerificarCargo() != "cliente") {
        return false;
    } else {
        return true;
    }
}

/**
 * Sumario do login
 * @param mixed $nome
 * @param mixed $endereco
 * @param mixed $pass
 * @return bool
 */


function verificarSenha($senha_digitada, $hash_armazenado) {
    $hash_senha_digitada = hash('sha256', $senha_digitada);
    return $hash_senha_digitada === $hash_armazenado;
}

function login($nome, $pass)
{
    $nome = escapeString($nome);
    $pass = escapeString($pass);
    $estado = "registado";

    // Busca o utilizador no banco de dados
    $sql = "SELECT * FROM utilizador
    WHERE nome = '$nome' 
    AND estado_conta = '$estado'";

    $resultado = executarQuery($sql);

    // Verificar se houve erro na query
    if ($resultado === false) {
        echo '<div class="input-group"><label>Erro ao aceder à base de dados.</label></div>';
        return false;
    }

    // Verificar se o utilizador foi encontrado
    if ($resultado && $resultado->num_rows >= 1) {
        $utilizador = $resultado->fetch_assoc();

        // Verifica se a senha está correta (comparando hash)
        if (isset($utilizador['secretpass']) && verificarSenha($pass, $utilizador['secretpass'])) {
            // Sucesso
            $_SESSION['user_id'] = $utilizador['id_utilizador'];
            $_SESSION['cargo'] = $utilizador['cargo'];
            return true;
        } else {
            echo '<div class="input-group"><label>Senha incorreta.</label></div>';
            return false;
        }
    } else {
        echo '<div class="input-group"><label>Utilizador não encontrado ou não registado.</label></div>';
    }
    return false;
}



function registarUti($nome, $endereco, $secretpass)
{
    $nome = escapeString($nome);
    $endereco = escapeString($endereco);
    $secretpass = escapeString($secretpass);
    $pass = hash('sha256', $secretpass);
    
    // Check if the name is already in use
    $sql = "SELECT nome FROM utilizador WHERE nome = '$nome'";
    $resultado = executarQuery($sql);
    
    if ($resultado->num_rows > 0) {
        header("Location: registar.php");
        echo "<h2>ERRO: O nome de utilizador já existe</h2>";
        exit;
    } else {
        // Create the SQL query to insert the new user
        $sql = "INSERT INTO utilizador (nome, endereco, secretpass)
                VALUES ('$nome', '$endereco', '$pass')";
        $resultado = executarQuery($sql);

        
        $sql = "SELECT * FROM utilizador WHERE nome = '$nome'
                AND endereco = '$endereco'";
        $resultado = executarQuery($sql);
        
        while ($row = $resultado->fetch_assoc()) {
            $id_utilizador = $row['id_utilizador'];
            $sql2 = "INSERT INTO carteira (id_utilizador, saldo_atual)
                    VALUES ('$id_utilizador', 0.00)";
            executarQuery($sql2);
        }
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
    if (Logged()) {
        $sql = "SELECT u.*, c.saldo_atual 
                  FROM utilizador u
                  LEFT JOIN carteira c ON u.id_utilizador = c.id_utilizador
                  WHERE u.id_utilizador = " . $_SESSION['user_id'];
        $result = executarQuery($sql);
        return $result->fetch_assoc();
    }
}