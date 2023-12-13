<?php

require_once("models/Users.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);


$type = filter_input(INPUT_POST, "type");

//verificar tipo de formular
if ($type === "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    if ($name && $lastname && $email && $password) {

        if ($password === $confirmpassword) {
            //verificar se o email é unico no sistema
            if ($userDAO->findByEmail($email) === false) {
                //echo"nenhum usuario com esse email";exit;

                $user = new User();

                //token e senhas
                $userToken = $user->generateToken();
                $finalpassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalpassword;
                $user->token = $userToken;

                $auth = True;

                $userDAO->create($user, $auth);

            } else {
                $message->setMessage("E-mail já cadastrado!", "error", "back");
            }
        } else {
            $message->setMessage("As senhas não são iguais", "error", "back");
        }
    } else {
        $message->setMessage("Por favor, preencha todos os campos", "error", "back");
    }



} else if ($type === "login") {
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");


    //Tenta Autenticar Usuario
    if ($userDAO->authenticateUser($email, $password)) {
        $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");

    } else {
        $message->setMessage("Usuário ou senha incorretos", "error", "back");

    }
} else {
    $message->setMessage("Informações Inválidas", "error", "index.php");
}
