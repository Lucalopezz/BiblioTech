<?php


// Verifica se usuário está autenticado
require_once("models/Adm.php");
require_once("dao/AdmDAO.php");
require_once("dao/BookDAO.php");
require_once("globals.php");
require_once("models/Message.php");
require_once("db.php");



$admDAO = new AdmUserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);

$admData = $admDAO->verifyTokenAdm(true);

$adm = new AdmUser();

require_once("templates/headerAdm.php");

$books = $bookDAO->getLatestBooks();

?>


<div id="main-container" class="container-fluid">
    <h2 class="section-title">Adiministração dos Livros</h2>
    <p class="section-desciption">
        Adicione / Edite / Exclua
    </p>

    <!--Adicionar -->
    <div class="col-md-12" id="add-book-container">
        <a href="<?= $BASE_URL ?>newbook.php" class="btn card-btn">
            <i class="fas fa-plus"></i> Adicionar Livro
        </a>
    </div>
    <!--Tabela -->
    <div class="col-md-12 " id="books-dashboard">
        <table class="table table-dark">
            <thead> <!-- cabeçalho da tabela -->
                <th scope="col">#</th>
                <th scope="col">Título</th>
                <th scope="col">Nota</th>
                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td scope="row">
                            <?= $book->id ?>
                        </td>
                        <td><a href="<?= $BASE_URL ?>book.php?id=<?= $book->id ?>" class="table-book-title">
                                <?= $book->title ?>
                            </a></td>
                        <td><i class="fas fa-star"></i>
                            9
                        </td>
                        <td class="actions-column">
                            <a href="<?= $BASE_URL ?>editbook.php?id=<?= $book->id ?>" class="edit-btn"><i
                                    class="far fa-edit"></i>Editar</a>
                            <form action="<?= $BASE_URL ?>book_process.php" method="post">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $book->id ?>">
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