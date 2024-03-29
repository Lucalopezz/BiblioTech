<?php
class Message
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }
    public function setMessage($msg, $type, $redirect = "index")
    {
        $_SESSION['msg'] = $msg;
        $_SESSION['type'] = $type;

        if ($redirect != "back") {
            header("Location: $this->url" . $redirect);
        } else {
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index";
            header("Location: $referer");
        }

    }
    public function getMessage()
    {
        if (!empty($_SESSION['msg'])) {
            return [
                "msg" => $_SESSION['msg'],
                "type" => $_SESSION['type']
            ];
        } else {
            return false;
        }
    }
    public function clearMessage()
    {
        $_SESSION['msg'] = '';
        $_SESSION['type'] = '';

    }
}


?>