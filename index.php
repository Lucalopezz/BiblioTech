<?php
require_once("templates/header.php");

require_once("dao/BookDAO.php");


$bookDAO = new BookDAO($conn, $BASE_URL);

$lastesBooks = $bookDAO->getLatestBooks();
$ficBooks = $bookDAO->getBooksByCategory("Ficção Científica");
$romBooks = $bookDAO->getBooksByCategory("Romance");
$AllBooks = $bookDAO->getAllBooks();
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Livros Novos</h2>
    <p class="section-desciption">
        Veja os últimos livros adicionados
    </p>
    <div class="books-container">
        <?php
        // pega os últimos 7 livros
        $last7Books = array_slice($lastesBooks, 0, 7);

        foreach ($last7Books as $book):
            ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($lastesBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>
    </div>



    <h2 class="section-title">Livros de Ficção Científica</h2>
    <p class="section-desciption">
        Veja os últimos livros de ficção científica adicionados
    </p>
    <div class="books-container">
        <?php
        // pega os últimos 7 livros
        $last7FicBooks = array_slice($ficBooks, 0, 7);

        foreach ($last7FicBooks as $book):
            ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($last7FicBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>
    </div>


    <h2 class="section-title">Livros de Romance</h2>
    <p class="section-desciption">
        Veja os últimos livros de romance adicionados
    </p>
    <div class="books-container">
        <?php
        // pega os últimos 7 livros
        $last7RomBooks = array_slice($romBooks, 0, 7);

        foreach ($last7RomBooks as $book):
            ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($last7RomBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Todos</h2>
    <p class="section-desciption">
        Veja todo o nosso estôque
    </p>
    <div class="books-container">
        <?php
        // pega os últimos 7 livros
        $last7AllBooks = array_slice($AllBooks, 0, 10);

        foreach ($last7AllBooks as $book):
            ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($AllBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>
        <?php if (count($AllBooks) >= 10): ?>
            <a class="read-more btn card-btn" href="<?= $BASE_URL ?>allBooks.php">Ver Mais</a>
        <?php endif; ?>
    </div>




</div>

<?php
require_once("templates/footer.php");
?>