<?php
class AdmUser
{
    public $id;
    public $user;
    public $password;
    public $token;


    public function generateToken()
    {
        return bin2hex(random_bytes(50)); //cria uma string e embaralha
    }


    
}

interface AdmUserDAOInterface
{
    public function buildAdm($data);
    public function updateAdm($token, $id);
    public function findByToken($token);
    public function verifyTokenAdm($protected = false);
    public function setTokenToSessionAdm($token, $redirect = true);
    public function authenticateAdmUser($user, $password);
    public function destroyTokenAdm();
}

?>