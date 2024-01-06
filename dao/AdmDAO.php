<?php

require_once("models/Adm.php");
require_once("models/Message.php");

class AdmUserDAO implements AdmUserDAOInterface
{
    private $conn;
    private $url;

    private $message;


    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);

    }
    public function buildAdm($data)
    {
        $adm = new AdmUser();
        $adm->id = $data['id'];
        $adm->user = $data['user'];
        $adm->password = $data['password'];
        $adm->token = $data['token'];

        return $adm;
    }
    public function createAdm(AdmUser $admUser, $authAdm = false)
    {
        $stmt = $this->conn->prepare("INSERT INTO adm (
            user, password, token
        ) VALUES (
            :user, :password, :token
        )");

        $stmt->bindParam(":user", $admUser->user);
        $stmt->bindParam(":password", $admUser->password);
        $stmt->bindParam(":token", $admUser->token);

        $stmt->execute();

        // Autenticar usuário caso o $authAdm seja true
        if ($authAdm === true) {
            $this->setTokenToSessionAdm($admUser->token);
        }
    }

    public function verifyTokenAdm($protected = false)
    {

        if (!empty($_SESSION["token"])) {

            // Pega o token da session
            $token = $_SESSION["token"];


            $adm = $this->findByToken($token);


            if ($adm) {

                return $adm;


            } else if ($protected) {

                // Redireciona usuário não autenticado caso o token n exista

                $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "back");


            }

        } else if ($protected) {

            // Redireciona usuário não autenticado
            $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "back");



        }
    }
    public function setTokenToSessionAdm($token, $redirect = true)
    {
        $_SESSION['token'] = $token;


        if ($redirect) {
            //redireciona para o perfil
            $this->message->setMessage("Seja bem-vindo!", "success", "controlPainel.php");



        }
    }
    public function updateAdm($token, $id)
    {
        $stmt = $this->conn->prepare("UPDATE adm SET token = :token WHERE id = :id");


        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":id", $id);

        $stmt->execute();


    }
    public function findByToken($token)
    {
        if ($token != "") {
            $stmt = $this->conn->prepare("SELECT * FROM adm WHERE token = :token");

            $stmt->bindParam(":token", $token);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                $adm = $this->buildAdm($data);

                return $adm;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function authenticateAdmUser($user, $password)
    {
        if ($user != "" && $password != "") {
            $stmt = $this->conn->prepare("SELECT * FROM adm WHERE user = :user");

            $stmt->bindParam(":user", $user);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch();
                $userAdm = $this->buildAdm($data);

                //Checar se as senhas batem
                if (password_verify($password, $userAdm->password)) {
                    //Gerar um token i inserir na seção
                    $token = $userAdm->generateToken();

                    $this->setTokenToSessionAdm($token, false);

                    //atualizar token no usuário
                    $id = $userAdm->id;
                    $this->updateAdm($token, $id);

                    return true;
                } else {
                    return false;
                }

            } else {

                return false;
            }

        } else {


            return false;
        }
    }
    public function destroyTokenAdm()
    {
        // Remove o token da session
        $_SESSION["token"] = "";



        // Redirecionar e apresentar a mensagem de sucesso
        $this->message->setMessage("Você fez o logout com sucesso!", "success", "painel.php");
    }
}