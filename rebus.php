<?php

include_once 'common/page.php';
include_once 'common/content.php';

use common\APage;
class Rebus extends \common\AContent {

    private string $user_data;
    private ?bool $is_user_data_correct = null;
    public function __construct() {
        if(isset($_POST['rebusText'])){
            $this->user_data = $_POST['rebusText'];
            if ($this->is_user_data_correct = $this -> filter_user_data()){
                $this->solve();
            }
        }
    }
    private function solve(){

    }
    private function filter_user_data() : bool
    {
        //$this->user_data = htmlspecialchars($this->user_data);
        $this->user_data = mb_strtoupper($this->user_data);
        $this->user_data = mb_ereg_replace("[^A-ZА-Я+*/=-]","",$this -> user_data );
        if (!preg_match('/^[A-ZА-Я]+(?:\s*[+*\/-]\s*[A-ZА-Я]+)*\s*=\s*[A-ZА-Я]+(?:\s*[+*\/-]\s*[A-ZА-Я]+)*$/', $this->user_data, $matches)){
            return false;
        }
        $letters = array_unique(mb_str_split(mb_ereg_replace('[^A-ZА-Я]', "", $this->user_data)));
        return sizeof($letters) <= 10;
    }

    public function create_content(){
        ?>
            <?php
                if ($this->is_user_data_correct === false){
            ?>
            <div class="alert alert-danger">
                Вы ввели неверный текст ребуса
            </div>
            <?php
                }
            ?>
            <form action="rebus.php" method = "POST">
                <div>
                    <label for="rebusText" class="form-label">Rebus Text</label>
                    <input type="text" class="form-control border-primary" id="rebusText"  name = "rebusText" required value="<?php print($this->user_data);?>">
                    <input type="submit" value="Solve" class="btn btn-primary m-2">
                </div>
            </form>
        <?php

    }
}

new APage(new Rebus());