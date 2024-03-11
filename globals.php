<?php

session_start();

$BASE_URL = "https://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') ;

?>