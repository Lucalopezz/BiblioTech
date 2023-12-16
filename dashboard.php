<?php
require_once("templates/header.php");

require_once("dao/UserDAO.php");
require_once("models/Users.php");
require_once("dao/BookDAO.php");
require_once("dao/ReviewDAO.php");


$userDAO = new UserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(True);


$userReviws = $reviewDAO->getReviewsByUser($userData->id);
//print_r($userReviws);
?>
<div id="main-container" class="container-fluid">
    <h2 class="section-title">Minhas Críticas</h2>
    <p class="section-desciption">Veja e edite suas críticas:</p>
    <div class="col-md-12" id="reviews-dashboard">
        <table class="table table-dark">
            <thead>
                <th scope="col">Texto</th>
                <th scope="col">Nota</th>

                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php foreach ($userReviws as $review): ?>
                    <tr>
                        <td><a href="<?= $BASE_URL ?>book.php?id=<?= $review['books_id'] ?>" class="table-book-title">
                                <?= $review['review'] ?>
                            </a></td>

                        <td><i class="fas fa-star"></i>
                            <?= $review['rating'] ?>
                        </td>
                        <td class="actions-column">
                            <a class="edit-btn" href="<?= $BASE_URL ?>editreview.php?id=<?= $review['id'] ?> " ><i
                                    class="far fa-edit"></i>Editar</a>
                            <form action="<?= $BASE_URL ?>review_process.php" method="post">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times"></i>Deletar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>


<?php
require_once("templates/footer.php");
?>