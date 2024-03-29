<?php

require_once ("models/Review.php");
require_once ("models/Message.php");

require_once ("dao/UserDAO.php");

class ReviewDAO implements ReviewDAOInterface
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
    public function buildReview($data)
    {


        $reviewObject = new Review();
        $reviewObject->id = $data['id'];
        $reviewObject->rating = $data['rating'];
        $reviewObject->review = $data['review'];
        $reviewObject->users_id = $data['users_id'];
        $reviewObject->books_id = $data['books_id'];

        return $reviewObject;


    }
    public function create(Review $review)
    {
        $stmt = $this->conn->prepare("INSERT INTO reviews (
            rating, review, books_id, users_id
          ) VALUES (
            :rating, :review, :books_id, :users_id
          )");

        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":books_id", $review->books_id);
        $stmt->bindParam(":users_id", $review->users_id);

        $stmt->execute();

        // Redireciona e apresenta mensagem de sucesso
        $this->message->setMessage("Crítica feita com sucesso!", "success", "back");
    }
    public function update(Review $review)
    {
        $stmt = $this->conn->prepare("UPDATE reviews SET rating = :rating, review = :review WHERE id = :id");

        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":id", $review->id);

        $stmt->execute();

        $this->message->setMessage("Comentário editado!", "success", "dashboard");
    }
    public function getBooksReview($id)
    {
        $reviews = [];
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE books_id = :books_id");

        $stmt->bindParam(":books_id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $reviewsData = $stmt->fetchAll();
            $userDAO = new UserDAO($this->conn, $this->url);
            foreach ($reviewsData as $review) {

                $reviewObject = $this->buildReview($review);

                //Chamar dados do usuario
                $user = $userDAO->findById($reviewObject->users_id);

                $reviewObject->user = $user;

                $reviews[] = $reviewObject;
            }

        }
        return $reviews;
    }
    public function getReviewsByUser($id)
    {
        $reviews = [];
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE users_id = :users_id");

        $stmt->bindParam(":users_id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $reviewsData = $stmt->fetchAll();

            foreach ($reviewsData as $review) {

                $reviews[] = $review;
            }

        }
        return $reviews;
    }
    public function findById($id)
    {
        $review = [];

        // Consulta para obter dados básicos do livro
        $stmtBook = $this->conn->prepare("SELECT * FROM reviews WHERE id = :id");
        $stmtBook->bindParam(":id", $id);
        $stmtBook->execute();

        if ($stmtBook->rowCount() > 0) {
            $reviewData = $stmtBook->fetch();
            $review = $this->buildReview($reviewData);

        }


        return $review;
    }

    public function hasAlreadyReviewed($id, $userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE books_id = :books_id AND users_id = :users_id");
        $stmt->bindParam(":books_id", $id);
        $stmt->bindParam(":users_id", $userId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getRatings($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE books_id = :books_id");

        $stmt->bindParam(":books_id", $id);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $rating = 0;

            $reviews = $stmt->fetchAll();

            foreach ($reviews as $review) {
                $rating += $review["rating"];
            }

            $rating = $rating / count($reviews);
            $rating = number_format($rating, 1);


        } else {

            $rating = "Não avaliado";

        }

        return $rating;
    }
    public function destroy($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $this->message->setMessage("Review deletado com sucesso!", "success", "dashboard");

    }

}

?>