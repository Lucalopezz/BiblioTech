<?php
class User
{
    public $id;
    public $name;
    public $lastname;
    public $gender;
    public $email;
    public $password;
 
    public $bio;
    public $token;

    public function getFullName($user)
    {
        return $user->name . " " . $user->lastname;
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(50)); //cria uma string e embaralha
    }
    public function generatePassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT); //cria uma string e embaralha
    }

}

interface UserDAOInterface
{
    public function buildUser($data);
    public function askGender($gen);
    public function create(User $user, $authUser = false);
    public function update(User $user, $redirect = true);
    public function verifyToken($protected = false);
    public function setTokenToSession($token, $redirect = true);
    public function authenticateUser($email, $password);
    public function findByEmail($email);
    public function findById($id);
    public function findByToken($token);
    public function changePassword(User $user);
    public function destroyToken();
}

?>