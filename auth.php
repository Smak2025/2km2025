<?php

include_once "common/content.php";
include_once "common/page.php";
class Auth extends \common\AContent {
    public function create_content(){
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