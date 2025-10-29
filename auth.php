<?php

include_once "common/content.php";
include_once "common/page.php";
class Auth extends \common\AContent {
    // Ниже временно расположены переменные с корректными логином и паролем.
    // В дальнейшем соответствующие данные нужно будет получать из базы данных.
    private $login;
    private $password;
    private ?bool $authorized = null;
    public function __construct()
    {
        parent::__construct();
        $this->is_opened = true;

        if (isset ($_GET['logout'])){
            unset($_SESSION['login']);
            header('Location: index.php');
        }

        if (isset($_POST['login'])){
            $this->login = htmlspecialchars($_POST['login']);
        }
        if (isset($_POST['password'])){
            $this->password = htmlspecialchars($_POST['password']);
        }
        if (isset($this->login) && isset($this->password)){
            $this->authorized = $this->autorize($this->login, $this->password);
            if ($this->authorized){
                $_SESSION['login'] = $this->login;
                if (isset($_SESSION['destination'])){
                    $dest = $_SESSION['destination'];
                    unset($_SESSION['destination']);
                    header("Location: $dest");
                } else {
                    header("Location: /");
                }
            }
        }

    }

    private function autorize($login, $password): bool{
        // return $login == $this->correct_login && $password == $this->correct_password;
        $f = fopen("users/users.json", "r");
        while(!feof($f)) {
            $data = fgets($f);
            $user = json_decode($data,true);
            if(isset($user['username']) && $user['username'] == $login) {
                if (isset($user['password']) && password_verify($password,$user['password'])) {
                    fclose($f);
                    return true;
                }
            }
        }
        fclose($f);
        return false;
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