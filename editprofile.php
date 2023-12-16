<?php
require_once("templates/header.php");
require_once("dao/UserDAO.php");
require_once("models/Users.php");

$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(True);

$user = new User();

$fullName = $user->getFullName($userData);

if ($userData->gender == "Feminino") {
    $image = "/img/users/mulher_icon.png";
} elseif ($userData->gender == "Masculino") {
    $image = "/img/users/homem.png";
} else {
    $image = "/img/users/user.png";
}

?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <form action="<?= $BASE_URL ?>user_process.php" method="post" id="edit-container" enctype="multipart/form-data">
            <input type="hidden" name="type" value="update">
            <div class="row">
                <div class="col-md-4">
                    <h1>
                        <a id="name-edit" href="<?=$BASE_URL?>profile.php?id=<?=$userData->id?>"><?= $fullName ?></a>
                    </h1>
                    <p class="page-description">
                        Altere seus dados no formulário abaixo:
                    </p>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o seu nome"
                            value="<?= $userData->name ?>">
                    </div>

                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname"
                            placeholder="Digite o seu sobrenome" value="<?= $userData->lastname ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" readonly class="form-control disabled" id="email" name="email"
                            placeholder="Digite o seu email" value="<?= $userData->email ?>">
                    </div>
                    <input type="submit" class="btn card-btn" value="Alterar">
                </div>

                <div class="col-md-4">
                    <div id="profile-image-container" style="background-image: url('<?= $BASE_URL . $image ?>');"></div>
                    <div class="form-group photo-edit">
                        <label for="bio">Seu Gênero:</label>
                        <select class="form-control" name="gender" id="gender">
                            <option>Selecione</option>
                            
                            <option value="Masculino" <?= $userData->gender == "Masculino" ? "selected" : "" ?>>
                                Masculino
                            </option>
                            <option value="Feminino" <?= $userData->gender == "Feminino" ? "selected" : "" ?>>
                                Feminino
                            </option>
                            <option value="Indefinido" <?= $userData->gender == "Indefinido" ? "selected" : "" ?>>
                                Outro
                            </option>
                        </select>
                    </div>

                    <div class="form-group photo-edit">
                        <label for="bio">Sobre você:</label>
                        <textarea class="form-control" name="bio" id="bio" rows="5"
                            placeholder="Conte quem você é, quais livros você gosta..."><?= $userData->bio ?></textarea>
                    </div>
                </div>
            </div>
        </form>


        <div class="row" id="change-password-container">
            <div class="col-md-4">
                <h2>Alterar a Senha:</h2>
                <p class="page-description">
                    Digite a nova senha e confirme para alterar sua senha:
                </p>
                <form action="<?= $BASE_URL ?>user_process.php" method="post" id="edit-container-password">
                    <input type="hidden" name="type" value="changepassword">
                    <input type="hidden" name="id" value="<?= $userData->id ?>">
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Digite o sua nova senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirmação de senha:</label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                            placeholder="Digite o sua nova senha novamente">
                    </div>
                    <input type="submit" class="btn card-btn" value="Alterar Senha">
                </form>
            </div>

        </div>
    </div>
</div>


<?php
require_once("templates/footer.php");
?>