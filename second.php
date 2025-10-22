<?php

include_once "common/content.php";
include_once "common/page.php";
class Second extends \common\AContent
{
    private ?int $user_value = null;
    private ?string $user_text = null;
    public function __construct()
    {
        parent::__construct();
        if (isset($_POST['val1'])) {
            $this->user_value = (int)$_POST['val1'];
        }

        if (isset($_POST['val2'])) {
            $this->user_text = htmlspecialchars($_POST['val2']);
            $this->user_text = mb_strtoupper($this->user_text);
        }
    }
    public function create_content(){
        ?>
        <form method="post" action="second.php">
            <label for="val1">Значение 1:</label>
            <input type="text" maxlength="8" name="val1" id="val1" placeholder="Введите тут число" class="text-primary">
            <label for="val2">Значение 2:</label>
            <input type="text" name="val2" id="val2" placeholder="Введите тут текст" class="text-primary">
            <input type="submit" class="btn btn-primary"/>
        </form>
        <?php
        if ($this->user_value != null){
            print ("Пользователь ввел число: " . $this->user_value);
            print ("<br>");
            print ("Пользователь ввел текст: " . $this->user_text);
        }
    }
}

new \common\APage(new Second());