<?php
require_once ("models/Book.php");
require_once ("models/Message.php");

require_once ("dao/ReviewDAO.php");


class BookDAO implements BookDAOInterface
{
    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildBook($data)
    {
        $book = new Book();
        $book->id = $data['id'];
        $book->title = $data['title'];
        $book->author = $data['author'];
        $book->description = $data['description'];
        $book->image = $data['image'];
        $book->quant = $data['quant'];
        $book->category = isset ($data['category']) ? $data['category'] : null;
        $book->pages = $data['pages'];


        //recebe as ratings do livro
        $reviewDao = new ReviewDao($this->conn, $this->url);

        $rating = $reviewDao->getRatings($book->id);

        $book->rating = $rating;

        return $book;

    }
    public function getLatestBooks()
    {
        $books = [];
        $stmt = $this->conn->query("SELECT * FROM books ORDER BY id DESC");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $booksArray = $stmt->fetchAll();
            foreach ($booksArray as $book) {
                $books[] = $this->buildBook($book);
            }
        }

        return $books;
    }
    public function getBooksByCategory($category)
    {
        $books = [];

        // Consulta para obter os IDs dos livros associados à categoria
        $stmt = $this->conn->prepare("SELECT bc.book_id FROM books_categories bc
                                      INNER JOIN categories c ON bc.category_id = c.id
                                      WHERE c.name = :category
                                      ORDER BY bc.id DESC");

        $stmt->bindParam(":category", $category);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $bookIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Consulta para obter os detalhes dos livros usando os IDs obtidos
            $inClause = implode(",", array_fill(0, count($bookIds), "?"));
            $stmtBooks = $this->conn->prepare("SELECT * FROM books WHERE id IN ($inClause) ORDER BY id DESC");
            $stmtBooks->execute($bookIds);

            $booksArray = $stmtBooks->fetchAll();
            foreach ($booksArray as $bookData) {
                $books[] = $this->buildBook($bookData);
            }
        }

        return $books;
    }
    public function getBooksForPage($livrosPorPagina, $paginaAtual)
    {
        $offset = ($paginaAtual - 1) * $livrosPorPagina;

        $stmt = $this->conn->prepare("SELECT * FROM books LIMIT :limit OFFSET :offset");
        $stmt->bindParam(":limit", $livrosPorPagina, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $books;
    }
    public function getAllBooks()
    {
        $books = [];

        // livros em ordem alfabética pelo título
        $stmt = $this->conn->query("SELECT * FROM books ORDER BY title ASC");

        if ($stmt->rowCount() > 0) {
            $booksArray = $stmt->fetchAll();
            foreach ($booksArray as $bookData) {
                $books[] = $this->buildBook($bookData);
            }
        }

        return $books;
    }
    public function findById($id)
    {
        $book = [];

        // Consulta para obter dados básicos do livro
        $stmtBook = $this->conn->prepare("SELECT * FROM books WHERE id = :id");
        $stmtBook->bindParam(":id", $id);
        $stmtBook->execute();

        if ($stmtBook->rowCount() > 0) {
            $bookData = $stmtBook->fetch();
            $book = $this->buildBook($bookData);

            // Consulta adicional para obter a categoria do livro
            $stmtCategory = $this->conn->prepare("SELECT c.name as category_name
                                             FROM books_categories bc
                                             LEFT JOIN categories c ON bc.category_id = c.id
                                             WHERE bc.book_id = :id");

            $stmtCategory->bindParam(":id", $id);
            $stmtCategory->execute();

            if ($stmtCategory->rowCount() > 0) {
                $categoryData = $stmtCategory->fetch();
                $book->category = $categoryData['category_name'];
            }
        }


        return $book;

    }


    public function findByTitle($title)
    {
        if ($title == "") {
            $this->message->setMessage("", "", "index");

        } else {
            $books = [];

            $stmt = $this->conn->prepare("SELECT * FROM books
                                        WHERE title LIKE :title");

            $stmt->bindValue(":title", '%' . $title . '%'); //esses % são para achar em todo o nome a letra

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $booksArray = $stmt->fetchAll();

                foreach ($booksArray as $book) {
                    $books[] = $this->buildBook($book);
                }

            }

            return $books;
        }


    }


    public function create(Book $book)
    {
        // Insere o livro na tabela 'books'
        $stmt = $this->conn->prepare("INSERT INTO books (
            title, author, description, image, quant, pages
          ) VALUES (
            :title, :author, :description, :image, :quant, :pages
          )");

        $stmt->bindParam(":title", $book->title);
        $stmt->bindParam(":author", $book->author);
        $stmt->bindParam(":description", $book->description);
        $stmt->bindParam(":image", $book->image);
        $stmt->bindParam(":quant", $book->quant);
        $stmt->bindParam(":pages", $book->pages);

        $stmt->execute();

        // Obtém o ID do livro recém-inserido
        $bookId = $this->conn->lastInsertId();


        $stmt2 = $this->conn->prepare("INSERT INTO books_categories (book_id, category_id) VALUES (:book_id, :category_id)");
        $stmt2->bindParam(":book_id", $bookId);
        $stmt2->bindParam(":category_id", $book->category);

        $stmt2->execute();


        // Redireciona e apresenta mensagem de sucesso
        $this->message->setMessage("Livro adicionado!", "success", "controlPainel");
    }


    public function findCategory()
    {
        $categories = [];
        $stmt = $this->conn->query("SELECT  id, name FROM categories ");
        $stmt->execute();


        while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $category;
        }


        return $categories;
    }
    public function createCategory($categoryName)
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (:categoryName);");

        $stmt->bindParam(":categoryName", $categoryName);

        $stmt->execute();

        $this->message->setMessage("Categoria adicionada!", "success", "controlPainel");

    }
    public function destroyCategory($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();


        $this->message->setMessage("Categoria deletado com sucesso!", "success", "controlPainel");

    }

    public function update(Book $book)
    {
        $stmt = $this->conn->prepare("UPDATE books SET title = :title, author = :author, description = :description, image = :image, quant = :quant, pages = :pages WHERE id = :id");

        $stmt->bindParam(":title", $book->title);
        $stmt->bindParam(":author", $book->author);
        $stmt->bindParam(":description", $book->description);
        $stmt->bindParam(":image", $book->image);
        $stmt->bindParam(":quant", $book->quant);
        $stmt->bindParam(":pages", $book->pages);
        $stmt->bindParam(":id", $book->id);

        $stmt->execute();



        $stmt2 = $this->conn->prepare("UPDATE books_categories SET book_id = :book_id, category_id = :category_id WHERE book_id = :id");

        $stmt2->bindParam(":book_id", $book->id); // Utilize diretamente o ID do livro
        $stmt2->bindParam(":category_id", $book->category);
        $stmt2->bindParam(":id", $book->id);

        $stmt2->execute();

        $this->message->setMessage("Livro editado!", "success", "controlPainel");

    }
    public function destroy($id)
    {
        // Obter o nome da imagem antes de excluir o livro
        $stmtGetImage = $this->conn->prepare("SELECT image FROM books WHERE id = :id");
        $stmtGetImage->bindParam(":id", $id);
        $stmtGetImage->execute();

        if ($stmtGetImage->rowCount() > 0) {
            $imageData = $stmtGetImage->fetch();
            $imageName = $imageData['image'];

            // Excluir a imagem do diretório 
            $imagePath = 'img/books/' . $imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        // Excluir a relação na tabela books_categories
        $stmt = $this->conn->prepare("DELETE FROM books_categories WHERE book_id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // Excluir o livro na tabela books
        $stmt2 = $this->conn->prepare("DELETE FROM books WHERE id = :id");
        $stmt2->bindParam(":id", $id);
        $stmt2->execute();

        $this->message->setMessage("Livro deletado com sucesso!", "success", "controlPainel");
    }
}