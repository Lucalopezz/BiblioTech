<?php
require_once("templates/headerAdm.php");


require_once("dao/AdmDAO.php");
require_once("dao/BookDAO.php");


$admDAO = new AdmUserDAO($conn, $BASE_URL);

$userData = $admDAO->verifyTokenAdm(True);
//pega o id da url
$id = filter_input(INPUT_GET, "id");


$bookDAO = new BookDAO($conn, $BASE_URL);

if (empty($id)) {
    $message->setMessage("Livro não encontrado!", "error", "controlPainel");

} else {
    $book = $bookDAO->findById($id);

    // verifica se o livro existe
    if (!$book) {
        $message->setMessage("Livro não encontrado!", "error", "controlPainel");

    }
}

//pega as categorias
$categories = $bookDAO->FindCategory();

?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1>
                    <?= $book->title ?>
                </h1>
                <p class="page-description">Altere os dados do livro no formulário abaixo:</p>
                <form id="edit-book-form" action="<?= $BASE_URL ?>book_process.php" method="post"
                    enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= $book->id ?>">

                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" placeholder="Digite o Título do seu Livro"
                            name="title" value="<?= $book->title ?>">
                    </div>
                    <div class="form-group">
                        <label for="title">Autor:</label>
                        <input type="text" class="form-control" id="author" placeholder="Digite o altor do seu Livro"
                            name="author" value="<?= $book->author ?>">
                    </div>

                    <div class="form-group">
                        <label for="image" class="bold">Imagem:</label>
                        <br>
                        <input type="file" class="form-control-file" name="image" id="image">
                    </div>

                    <div class="form-group">
                        <label for="pages">Páginas:</label>
                        <input type="text" class="form-control" id="pages"
                            placeholder="Digite o número de páginas do livro" name="pages" value="<?= $book->pages ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria:</label>
                       
                        <select name="category" id="category" class="form-control">

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $book->category == $category['name'] ? "selected" : "" ?>>
                                    <?= $category['name'] ?>
                                </option>
                            <?php endforeach; ?>


                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quant">Quantidade:</label>
                        <input type="text" class="form-control" id="quant"
                            placeholder="Insira a quantidade de livros no estoque" name="quant"
                            value="<?= $book->quant ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" placeholder="Descreva o Filme" rows="3"
                            class="form-control"><?= $book->description ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar Livro">
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