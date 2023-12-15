<?php
require_once("templates/header.php");
require_once("dao/ReviewDAO.php");
require_once("dao/UserDAO.php");
require_once("dao/BookDAO.php");


$userDAO = new UserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(True);


$id = filter_input(INPUT_GET, "id");
if (empty($id)) {
    $message->setMessage("Review não encontrado!", "error", "index.php");

} else {
    $review = $reviewDAO->findById($id);
    $book = $bookDAO->findById($review->books_id);
    //print_r($review);
    // verifica se o livro existe
    if (!$review) {
        $message->setMessage("Filme não encontrado!", "error", "index.php");

    }
}

//echo $review->books_id;
//print_r($book);exit;

?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1>
                    <?= $book->title ?>
                </h1>
                <p class="page-description">Altere o texto do comentário no formulário abaixo:</p>
                <form id="edit-book-form" action="<?= $BASE_URL ?>review_process.php" method="post"
                    enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= $review->id ?>">
                    <input type="hidden" name="idBook" value="<?= $review->books_id ?>">

                    <div class="form-group">
                        <label for="rating">Nota:</label>
                        <select name="rating" id="nota" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1" <?= $review->rating == 1 ? "selected" : "" ?>>1</option>
                                <option value="2" <?= $review->rating == 2 ? "selected" : "" ?>>2</option>
                                <option value="3" <?= $review->rating == 3 ? "selected" : "" ?>>3</option>
                                <option value="4" <?= $review->rating == 4 ? "selected" : "" ?>>4</option>
                                <option value="5" <?= $review->rating == 5 ? "selected" : "" ?>>5</option>
                                <option value="6" <?= $review->rating == 6 ? "selected" : "" ?>>6</option>
                                <option value="7" <?= $review->rating == 7 ? "selected" : "" ?>>7</option>
                                <option value="8" <?= $review->rating == 8 ? "selected" : "" ?>>8</option>
                                <option value="9" <?= $review->rating == 9 ? "selected" : "" ?>>9</option>
                                <option value="10" <?= $review->rating == 10 ? "selected" : "" ?>>10</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="review">Comentário:</label>
                        <textarea name="review" id="review" placeholder="Seu comentário" rows="3"
                            class="form-control"><?= $review->review ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar Comentário">
                </form>
            </div>
            <div class="col-md-3 " id="image-edit">
                <div class="book-image-container"
                    style="background-image: url('<?= $BASE_URL ?>img/books/<?= $book->image ?>');"></div>
            </div>
        </div>
    </div>
</div>

<?php
require_once("templates/footer.php");

?>