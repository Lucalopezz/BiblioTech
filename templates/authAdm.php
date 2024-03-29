<div id="main-container" class="container-fluid">
    <div id="template-login">
        <div class="col-md-12">
            <div class="row" id="auth-row">
                <div class="col-md-4" id="login-container">
                    <h2>Entrar</h2>
                    <form action="<?= $BASE_URL ?>authPainel_process" method="post">
                        <input type="hidden" value="login" name="type">
                        <div class="form-group">
                            <label for="user">User:</label>
                            <input type="text" name="user" id="user" class="form-control"
                                placeholder="Digite seu user">
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Digite sua senha">
                        </div>
                        <input type="submit" class="btn card-btn" value="Entrar">

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>