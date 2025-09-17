<?php

include_once "common/content.php";
include_once "common/page.php";
class Second extends \common\AContent
{
    private ?int $user_value = null;
    public function __construct()
    {
        if (isset($_POST['val1'])) {
            $this->user_value = (int)$_POST['val1'];
        }
    }
    public function create_content(){
        ?>
        <form method="post" action="second.php">
            <label for="val1">Значение 1:</label>
            <input type="text" maxlength="8" name="val1" id="val1" placeholder="Введите тут число" class="text-primary">
            <input type="submit" class="btn btn-primary"/>
        </form>
        <?php
        if ($this->user_value != null){
            print ("Пользователь ввел: " . $this->user_value);
        }
    }
}

new \common\APage(new Second());