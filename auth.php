<?php

include_once "common/content.php";
include_once "common/page.php";
class Auth extends \common\AContent {
    // Ниже временно расположены переменные с корректными логином и паролем.
    // В дальнейшем соответствующие данные нужно будет получать из базы данных.
    private $correct_login = "user";
    private $correct_password = "pwd12345";
    private $login;
    private $password;
    private ?bool $authorized = null;
    public function __construct()
    {
        if (isset($_POST['login'])){
            $this->login = htmlspecialchars($_POST['login']);
        }
        if (isset($_POST['password'])){
            $this->password = htmlspecialchars($_POST['password']);
        }
        if (isset($this->login) && isset($this->password)){
            $this->authorized = $this->autorize($this->login, $this->password);
        }

    }

    private function autorize($login, $password): bool{
        return $login == $this->correct_login && $password == $this->correct_password;
    }

    public function create_content(): void
    {
        if (isset($this->authorized)){
            if ($this->authorized === true){
                print ('<div class="alert alert-success show" role="alert">Привет, '.$this->login.'!</div>');
            } else {
                print ('<div class="alert alert-danger show" role="alert">Пользователь не авторизован!</div>');
            }
        }
        ?>
            <form method="post" action="auth.php">
                <div class="container align-content-center w-50 m-auto border border-1">
                    <div class="row align-items-center justify-content-center m-auto mb-3">
                        <div class="col col-2 text-end"><label for="login" class="form-label">Логин: </label></div>
                        <div class="col col-auto"><input type="text" class="form-control" maxlength="50" placeholder="Login" id="login" name="login"></div>
                    </div>
                    <div class="row align-items-center justify-content-center m-auto mb-3">
                        <div class="col col-2 text-end"><label for="password" class="form-label">Пароль: </label></div>
                        <div class="col col-auto"><input type="password" class="form-control" maxlength="50" placeholder="Password" id="password" name="password"></div>
                    </div>
                    <div class="row justify-content-center m-auto">
                        <div class="col col-auto"><input type="submit" class="btn btn-primary" value="Вход"></div>
                    </div>
                </div>
            </form>
        <?php
    }

}

new \common\APage(new Auth());