<?php 
require_once("templates/header.php");
require_once("dao/BookDAO.php");

$bookDAO = new BookDAO($conn, $BASE_URL);
$AllBooks = $bookDAO->getAllBooks();

?>
<div id="main-container" class="container-fluid">
<div class="books-container">
        <?php

        foreach ($AllBooks as $book):
            ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($AllBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>

    </div>
</div>
<?php 
require_once("templates/footer.php");
?>