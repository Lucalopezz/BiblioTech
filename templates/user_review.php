<?php
require_once ('models/Users.php');
$userModel = new User();
$fullName = $userModel->getFullName($review->user);

if ($review->user->gender == "Feminino") {
    $image = "/img/users/mulher_icon.png";
} elseif ($review->user->gender == "Masculino") {
    $image = "/img/users/homem.png";
} else {
    $image = "/img/users/user.png";
}

?>


<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL . $image ?>')">
            </div>
        </div>
        <div class="col-md-9">
            <h4 class="author-name"><a href="<?= $BASE_URL ?>profile?id=<?= $review->user->id ?>">
                    <?= $fullName ?>
                </a></h4>
            <p><i class="fas fa-star"></i>
                <?= $review->rating ?>
            </p>
        </div>
        <div class="col-md-12">
            <p class="comment-title">Comentário:</p>
            <p>
                <?= $review->review ?>
            </p>
        </div>
    </div>
</div>