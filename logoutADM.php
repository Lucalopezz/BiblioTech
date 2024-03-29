<?php


require_once ("dao/AdmDAO.php");
require_once ("globals.php");
require_once ("db.php");


$admDAO = new AdmUserDAO($conn, $BASE_URL);

$admDAO->destroyTokenAdm();
