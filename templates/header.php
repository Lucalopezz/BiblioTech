<?php
require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$flassMesage = $message->getMessage();

if (!empty($flassMesage['msg'])) {
    // limpar a msg
    $message->clearMessage();
}
$userDao = new UserDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(false);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTech</title>
    <link rel="shortcut icon" href="<?= $BASE_URL ?>img/bibliotech.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css"
        integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw=="
        crossorigin="anonymous" />
    <!--Font-Awnsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--CSS -->
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">
</head>

<body>
    <header>
        <nav id="main-navbar" class="navbar navbar-expand-lg">
            <a href="<?= $BASE_URL ?>" class="navbar-brand">
                <i class="fa-solid fa-book" id="logo"></i>
                <span id="bibliotech-title">Bibliotech</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <form action="<?= $BASE_URL ?>search.php" method="GET" id="search-form">
                <div class="input-group"> <!-- Adicionando um elemento de grupo para melhor alinhamento -->
                    <input type="text" name="q" id="search" class="form-control" placeholder="Buscar livro"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <?php if ($userData): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $BASE_URL ?>dashboard.php"><i class="fa-solid fa-star"></i> Minhas Críticas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bold" href="<?= $BASE_URL ?>editprofile.php">
                                <i class="fa-solid fa-user"></i>
                                <?= $userData->name ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $BASE_URL ?>logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $BASE_URL ?>authUser.php">Entrar / Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <?php if (!empty($flassMesage["msg"])): ?>
        <div class="msg-container">
            <p class="msg success <?= $flassMesage["type"] ?>">
                <?= $flassMesage["msg"] ?>
            </p>
        </div>
    <?php endif; ?>