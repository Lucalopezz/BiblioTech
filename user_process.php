<?php
require_once("models/Users.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);


$type = filter_input(INPUT_POST, "type");


if ($type === "update") {
  //print_r($_POST); exit;
  $userData = $userDAO->verifyToken();

  //print_r($userData);exit;
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");
  $gender = filter_input(INPUT_POST, "gender");

  $user = new User();

  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;
  $userData->gender = $gender;

  $userDAO->update($userData);

} elseif ($type === "changepassword") {
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  $userData = $userDAO->verifyToken();
  $id = $userData->id;

  if ($password == $confirmpassword) {
    //novo usuario
    $user = new User();
    $finalpassword = $user->generatePassword($password);

    $user->password = $finalpassword;
    $user->id = $id;

    $userDAO->changePassword($user);

  } else {
    $message->setMessage("As senhas não batem!", "error", "back");

  }


} else {
  $message->setMessage("Informações Inválidas", "error", "index.php");
}