<?php
require_once("templates/header.php");

require_once("dao/BookDAO.php");
require_once("dao/ReviewDAO.php");
require_once("models/Book.php");

//pegar o id do livro


$id = filter_input(INPUT_GET, "id");
$userData;
$book;

$bookDAO = new BookDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

if (empty($id)) {
    $message->setMessage("Livro não encontrado!", "error", "index.php");

} else {
    $book = $bookDAO->findById($id);

    // verifica se o livro existe
    if (!$book) {
        $message->setMessage("Livro não encontrado!", "error", "index.php");

    }
}
//checar se o livro tem imagem
if ($book->image == "") {
    $book->image = "movie_cover.jpg";
}
if (!empty($userData)) {
    //resgatar as reviews do livro
    $alreadyReviewed = $reviewDAO->hasAlreadyReviewed($id, $userData->id);
}
$bookReviews = $reviewDAO->getBooksReview($id);

//print_r($bookReviews);
?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 book-container" id="book-cont">
            <h1 class="page-title">
                <?= $book->title ?>
            </h1>
            <p class="book-details">
                <span>Páginas:
                    <?= $book->pages ?>
                </span>
                <span class="pipe"></span>
                <span>
                    <?= $book->category ?>
                </span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i>
                    <?= $book->rating ?>
                </span>
            </p>

            <div class="book-image-container"
                style="background-image: url(<?= $BASE_URL ?>img/books/<?= $book->image ?>);">
            </div>
            <p class="description">
                <?= $book->description ?>
            </p>


        </div>

        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações</h3>
            <!-- Verifica se habilita a review para o usuario logado -->
            <?php if (!empty($userData) && !$alreadyReviewed): ?>
                <div class="col-md-12" id="form-container">
                    <h4>Envie sua avaliação:</h4>
                    <p class="page-description">Preencha o form com a nota e comentário sobre o livro</p>
                    <form action="<?= $BASE_URL ?>review_process.php" method="post" id="review-form-container">
                        <input type="hidden" name="type" value="create">
                        <input type="hidden" name="books_id" value="<?= $book->id ?>">
                        <div class="form-group">
                            <label for="rating">Nota do livro:</label>
                            <select class="form-control" name="rating" id="rating">
                                <option value="">Selecione</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" placeholder="Deixe seu comentário"
                                class="form-control" maxlength="100"></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Enviar comentário">

                    </form>
                </div>
            <?php endif; ?>

            <?php foreach ($bookReviews as $review): ?>
                <?php require("templates/user_review.php"); ?>
            <?php endforeach; ?>
            <?php if (count($bookReviews) == 0): ?>
                <p class="empty-list">Não há críticas para este filme ainda...</p>
            <?php endif; ?>

        </div>
    </div>
</div>


<?php
require_once("templates/footer.php");