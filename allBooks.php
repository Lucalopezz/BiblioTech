<?php
require_once("templates/header.php");
require_once("dao/BookDAO.php");
require_once("dao/ReviewDAO.php");

$bookDAO = new BookDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);
// Configurações de paginação
$livrosPorPagina = 15;
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Recupera a lista de livros para a página atual, junto com os reviews
$AllBooks = $bookDAO->getBooksForPage($livrosPorPagina, $paginaAtual);
foreach($AllBooks as $book){
    $book->rating = $reviewDAO->getRatings($book->id);
}

// Calcula o número total de páginas
$totalPaginas = ceil(count($bookDAO->getAllBooks()) / $livrosPorPagina);
?>
<div id="main-container" class="container-fluid">
    <div class="books-container">
        <?php foreach ($AllBooks as $book): ?>
            <?php require('templates/book_card.php'); ?>
        <?php endforeach; ?>

        <?php if (count($AllBooks) == 0): ?>
            <p class="empty-list">Não há livros cadastrados</p>
        <?php endif; ?>
    </div>
    <!-- Controles de Navegação -->
    <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a href="?pagina=<?= $paginaAtual - 1 ?>">Página Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" <?= $paginaAtual == $i ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaAtual + 1 ?>">Próxima Página</a>
            <?php endif; ?>
        </div>
</div>
<?php
require_once("templates/footer.php");
?>