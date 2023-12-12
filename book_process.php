<?php

require_once("models/Book.php");
require_once("models/Message.php");
require_once("dao/AdmDAO.php");
require_once("dao/BookDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$admDAO = new AdmUserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);


$type = filter_input(INPUT_POST, "type"); // tipo do form

//Resgata dados do usuário, vendo se ele está logado
$admData = $admDAO->verifyTokenAdm();

if ($type == "create") {
    // Obter dados do formulário
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $pages = filter_input(INPUT_POST, "pages");
    $category = filter_input(INPUT_POST, "category");
    $quant = filter_input(INPUT_POST, "quant");

    // Verificar se os campos essenciais estão preenchidos
    if ($title !== null && $quant !== null) {
        $book = new Book();
        $book->title = $title;
        $book->description = $description;
        $book->category = $category;
        $book->pages = $pages;
        $book->quant = $quant;

        // Processar a imagem, se presente
        if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
            $image = $_FILES['image'];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];

            // Checar se o tipo de imagem é válido
            if (in_array($image["type"], $imageTypes)) {
                $imageFile = imagecreatefromstring(file_get_contents($image['tmp_name']));
                $imageName = $book->imageGenerateName();
                imagejpeg($imageFile, "./img/books/" . $imageName, 100);
                $book->image = $imageName;
            } else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            }
        }

        // Se tudo estiver correto, chame o método create
        $bookDAO->create($book);
    } else {
        $message->setMessage("Faltam Informações, Coloque Título e Quantidade!", "error", "back");
    }
} else if ($type == "delete") {
    $id = filter_input(INPUT_POST, "id");
    $book = $bookDAO->findById($id);

    if ($book) {

        $bookDAO->destroy($book->id);

    } else {
        $message->setMessage("Informações Inválidas", "error", "index.php");
    }

} else if ($type == "update") {
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $pages = filter_input(INPUT_POST, "pages");
    $category = filter_input(INPUT_POST, "category");
    $quant = filter_input(INPUT_POST, "quant");
    $id = filter_input(INPUT_POST, "id");


    $bookData = $bookDAO->findById($id);

    if ($bookData) {
        if (!empty($title) && !empty($quant)) {
            $bookData->title = $title;
            $bookData->description = $description;
            $bookData->category = $category;
            $bookData->pages = $pages;
            $bookData->quant = $quant;

            if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
                $image = $_FILES['image'];
                $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                $jpgArray = ["image/jpg", "image/jpeg"];

                // Checagem de tipo de imagem
                if (in_array($image["type"], $imageTypes)) {

                    // Checar se jpg
                    if (in_array($image['type'], $jpgArray)) {

                        $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                        // Imagem é png
                    } else {

                        $imageFile = imagecreatefrompng($image["tmp_name"]);

                    }
                    $imageName = $book->imageGenerateName();

                    imagejpeg($imageFile, "./img/books/" . $imageName, 100);

                    $bookData->image = $imageName;

                } else {
                    $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

                }
            }
           
            $bookDAO->update($bookData);


        } else {
            $message->setMessage("Faltam Informações!", "error", "back");

        }
    } else {
        $message->setMessage("Informações erradas, livro não existe!", "error", "controlPainel.php");

    }


}else if ($type == "create_category") {
    $name = filter_input(INPUT_POST, "name");

    if (!empty($name)) {
        $categoryName = $name;

        $bookDAO->createCategory($categoryName);
    }
}else if ($type == "delete_category"){
    $id = filter_input(INPUT_POST, "id");

    if($id){
        $bookDAO->destroyCategory($id);
    }else {
        $message->setMessage("Informações Inválidas", "error", "index.php");
    }

}else {
    $message->setMessage("Informações erradas!", "error", "index.php");

}

