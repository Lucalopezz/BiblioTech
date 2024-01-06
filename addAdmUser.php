<?php
require_once("models/Adm.php");
require_once("dao/AdmDAO.php");
require_once("dao/BookDAO.php");
require_once("globals.php");
require_once("models/Message.php");
require_once("db.php");



$admDAO = new AdmUserDAO($conn, $BASE_URL);
$bookDAO = new BookDAO($conn, $BASE_URL);

// Verifica se usuário está autenticado
$admData = $admDAO->verifyTokenAdm(true);


require_once("templates/headerAdm.php");


?>


<div id="main-container" class="container-fluid">
    <div id="register-container">
        <h2>Criar Adm</h2>
        <form action="<?= $BASE_URL ?>authPainel_process.php" method="post">
            <input type="hidden" value="register" name="type">
            <div class="form-group">
                <label for="user">User:</label>
                <input type="text" name="user" id="user" class="form-control" placeholder="Digite seu usuario">
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Digite sua senha">
            </div>
            <div class="form-group">
                <label for="confirmpassword">Confirmação de Senha:</label>
                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control"
                    placeholder="Confirme sua senha">
            </div>


            <input type="submit" class="btn card-btn" value="Criar">

        </form>
    </div>
</div>

<?php 
require_once('templates/footer.php')
?>