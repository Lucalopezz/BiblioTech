<?php
require_once("models/Adm.php");
require_once("dao/AdmDAO.php");
require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");

$admDAO = new AdmUserDAO($conn, $BASE_URL);
$message = new Message($BASE_URL);

$type = filter_input(INPUT_POST, "type");


if ($type == "login") {
    $user = filter_input(INPUT_POST, "user");
    $password = filter_input(INPUT_POST, "password");


    //Tenta Autenticar Usuario
    if ($admDAO->authenticateAdmUser($user, $password)) {
        $message->setMessage("Seja bem-vindo!", "success", "controlPainel.php");


    } else {
        $message->setMessage("Usuário ou senha incorretos", "error", "back");


    }
} else {
    $message->setMessage("Informações Inválidas", "error", "index.php");
    
}




?>