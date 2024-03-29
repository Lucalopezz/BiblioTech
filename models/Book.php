<?php 
class Book
{


    public $id;
    public $title;
    public $author;
    public $description;
    public $image;
    public $quant;
    public $category;
    public $pages;
    
    public $rating;

    public function imageGenerateName()
    {
        return bin2hex(random_bytes(60)) . ".jpg";
    }



}

interface BookDAOInterface
{
    public function buildBook($data);
    public function getLatestBooks();
    public function getAllBooks();
    public function getBooksByCategory($category);
    public function findById($id);
    public function findByTitle($title);
    public function findCategory();
    public function getBooksForPage($livrosPorPagina, $paginaAtual);
    public function create(Book $book);
    public function createCategory($categoryName);
    public function update(Book $book);
    public function destroy($id);
    public function destroyCategory($id);
}


?>