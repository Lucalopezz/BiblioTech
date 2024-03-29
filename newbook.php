<?php

require_once ("models/Adm.php");
require_once ("dao/AdmDAO.php");
require_once ("dao/BookDAO.php");
require_once ("globals.php");
require_once ("models/Message.php");
require_once ("db.php");
require_once ("templates/headerAdm.php");

$bookDAO = new BookDAO($conn, $BASE_URL);

$admDAO = new AdmUserDAO($conn, $BASE_URL);

$admData = $admDAO->verifyTokenAdm(true);

//pega as categorias
$categories = $bookDAO->FindCategory();


?>

<div id="main-container" class="container-fluid">

    <div class="offset-md-4 col-md-4 new-book-container">
        <h1 class="page-title">Adicionar Livro</h1>

        <form action="<?= $BASE_URL ?>book_process.php" method="post" id="add-book-form" enctype="multipart/form-data">
            <input type="hidden" name="type" value="create">

            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" class="form-control" id="title" placeholder="Digite o Título do seu Livro"
                    name="title">
            </div>

            <div class="form-group">
                <label for="image" class="bold">Imagem:</label>
                <br>
                <input type="file" class="form-control-file" name="image" id="image">
            </div>

            <div class="form-group">
                <label for="title">Autor:</label>
                <input type="text" class="form-control" id="author" placeholder="Digite o altor do seu Livro"
                    name="author" >
            </div>

            <div class="form-group">
                <label for="pages">Páginas:</label>
                <input type="text" class="form-control" id="pages" placeholder="Digite o Número de Páginas do seu Livro"
                    name="pages">
            </div>

            <div class="form-group">
                <label for="category">Categoria:</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Selecione</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>">
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quant">Estôque:</label>
                <input type="text" class="form-control" id="quant" placeholder="Quantos livros no estôque" name="quant">
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" placeholder="Descreva o Livro" rows="3"
                    class="form-control"></textarea>
            </div>
            <input type="submit" class="btn card-btn" value="Adicionar Livro">

        </form>

    </div>

</div>



<?php
require_once ("templates/footer.php");
?>