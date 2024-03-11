<?php

$db_name = 'bibliotech';
$db_host = 'mysql.bibliotech.app.br';
$db_user = 'bibliotech';
$db_pass = 'lol040989';

$conn = new PDO("mysql:dbname=" . $db_name . ";host=" . $db_host, $db_user, $db_pass);


//habilitar erros PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


?>