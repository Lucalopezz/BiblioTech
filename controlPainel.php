<?php
require_once ("models/Adm.php");
require_once ("dao/AdmDAO.php");
require_once ("dao/BookDAO.php");
require_once ("globals.php");
require_once ("models/Message.php");
require_once ("db.php");



$admDAO = new AdmUserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);

// Verifica se usuário está autenticado
$admData = $admDAO->verifyTokenAdm(true);

$adm = new AdmUser();

require_once ("templates/headerAdm.php");

$books = $bookDAO->getLatestBooks();
$categories = $bookDAO->FindCategory();

?>


<div id="main-container" class="container-fluid">
    <h2 class="section-title">Adiministração dos Livros</h2>
    <p class="section-desciption">
        Adicione / Edite / Exclua
    </p>

    <!--Adicionar -->
    <div class="col-md-12" id="add-book-container">
        <a href="<?= $BASE_URL ?>newbook" class="btn card-btn">
            <i class="fas fa-plus"></i> Adicionar Livro
        </a>
    </div>
    <!--Tabela de livros -->
    <div class="col-md-12 " id="books-dashboard">
        <table class="table table-bordered">
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
                        <td><a href="<?= $BASE_URL ?>book?id=<?= $book->id ?>" class="table-book-title">
                                <?= $book->title ?>
                            </a></td>
                        <td><i class="fas fa-star"></i>
                            <?= $book->rating ?>

                        </td>
                        <td class="actions-column">
                            <a href="<?= $BASE_URL ?>editbook?id=<?= $book->id ?>" class="edit-btn"><i
                                    class="far fa-edit"></i></a>
                            <form action="<?= $BASE_URL ?>book_process" method="post">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $book->id ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <br>
    <br>


    <h2 class="section-title mt-5 ">Adiministração de Categorias</h2>
    <p class="section-desciption">
        Adicione / Exclua
    </p>
    <!--Tabela de categorias -->
    <div class="col-md-12 " id="books-dashboard">
        <table class="table table-bordered">
            <thead>
                <th scope="col">#</th>
                <th scope="col">Categorias</th>
                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td scope="row">
                            <?= $category['id'] ?>
                        </td>
                        <td>
                            <?= $category['name'] ?>
                        </td>

                        <td class="actions-column">

                            <form action="<?= $BASE_URL ?>book_process" method="post">
                                <input type="hidden" name="type" value="delete_category">
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!--Adicionar Categoria -->
    <div class="row">
        <div class="col-md-9" id="add-book-container"></div>

        <div class="col-md-3" id="add-book-container">

            <form action="<?= $BASE_URL ?>book_process" method="post" id="add-category-form"
                enctype="multipart/form-data">
                <h3 class="section-title mt-5 ">Adição de Categorias</h3>

                <input type="hidden" name="type" value="create_category">

                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" id="name" placeholder="Digite a Categoria" name="name">
                </div>
                <input type="submit" class="btn card-btn" value="Adicionar Categoria">

            </form>

        </div>
    </div>

</div>

<?php
require_once ("templates/footer.php");
?>