<?php 
require_once("dao/UserDAO.php");
require_once("dao/ReviewDAO.php");
require_once("dao/BookDAO.php");
require_once("globals.php");
require_once("db.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type"); 
$userData = $userDAO->verifyToken();

//echo $type;exit;
if ($type === 'create') {

    // Recebendo dados do post
    $rating = filter_input(INPUT_POST,"rating");
    $review = filter_input(INPUT_POST,"review");
    $books_id = filter_input(INPUT_POST,"books_id");
    $users_id = $userData->id;

    $reviewObject = new Review();

    $bookData = $bookDAO->findById($books_id);
    //vendo se o filme existe
    if($bookData){
        //verifica dados min
        if(!empty($rating) && !empty($review) && !empty($books_id)){
            $reviewObject->rating = $rating;
            $reviewObject->review = $review;
            $reviewObject->books_id = $books_id;
            $reviewObject->users_id = $users_id;

            $reviewDAO->create($reviewObject);
        }else{
            $message->setMessage("Informações Faltando, preencha todos os campos!", "error", "back");
            
        }
    }else{
        $message->setMessage("Informações Inválidas 1", "error", "index.php");
    }

}else if($type === 'update'){
    $rating = filter_input(INPUT_POST,"rating");
    $review = filter_input(INPUT_POST,"review");
    $id = filter_input(INPUT_POST,"id");
    $idBook = filter_input(INPUT_POST,"idBook");
    

    $reviewObject = new Review();

    $bookData = $bookDAO->findById($idBook);
    //print_r($bookData->id);exit;

    if($bookData){
        //verifica dados min
        if(!empty($rating) && !empty($review)){
            $reviewObject->rating = $rating;
            $reviewObject->review = $review;
            $reviewObject->id = $id;

            $reviewDAO->update($reviewObject);
        }else{
            $message->setMessage("Informações Faltando, preencha todos os campos!", "error", "back");
            
        }
    }else{
        $message->setMessage("Informações Inválidas!", "error", "index.php");
    }

}else if($type === 'delete'){
    $id = filter_input(INPUT_POST, "id");
    $review = $reviewDAO->findById($id);
    if($review) {

        $reviewDAO->destroy($review->id);

    } else {
        $message->setMessage("Informações Inválidas!", "error", "index.php");
    }

}else {
    $message->setMessage("Informações Inválidas!", "error", "index.php");

}
?>