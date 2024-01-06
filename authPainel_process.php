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
} else if ($type == "register") {
    $user = filter_input(INPUT_POST, "user");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    if ($user && $password) {

        if ($password === $confirmpassword) {
            $adm = new AdmUser();

            $admToken = $adm->generateToken();
            $finalpassword = $adm->generatePassword($password);


            $adm->user = $user;
            $adm->password = $finalpassword;
            $adm->token = $admToken;

            $authAdm = true;
            $admDAO->createAdm($adm, $authAdm);


        } else {
            $message->setMessage("As senhas não são iguais", "error", "back");
        }

    } else {
        $message->setMessage("Por favor, preencha todos os campos", "error", "back");
    }

} else {
    $message->setMessage("Informações Inválidas", "error", "index.php");

}




?>