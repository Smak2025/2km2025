<?php

include_once 'common/page.php';
include_once 'common/content.php';

use common\APage;
class Rebus extends \common\AContent {

    private string $user_data;
    private ?bool $is_user_data_correct = null;

    private array $letters = [];
    public function __construct() {
        parent::__construct();
        if(isset($_POST['rebusText'])){
            $this->user_data = $_POST['rebusText'];
            if ($this->is_user_data_correct = $this -> filter_user_data()){
                $this->solve();
            }
        }
    }
    private function solve(){
        foreach ($this-> generate_dictionary($this->letters)as $dict){
            $expression = "";
            foreach (mb_str_split($this->user_data)as $char){
                $expression.= $dict[$char]??$char;
                if($char == '=') $expression .= '=';
            }
            eval('$check = '.$expression.';');
            if ($check) print($expression.'<br>');
        }
    }
    private function generate_dictionary($letters, $used_digits = []): Generator
    {
        $letters = array_values($letters);
        $ltr = $letters[0];
        unset($letters[0]);
        $digits = array_values(array_diff(range(0,9), $used_digits));
        foreach ($digits as $digit) {
            $res = [$ltr=>$digit];
            //print("<br>\nres = ");
            //print_r($res);
            $used = $used_digits;
            $used[] = $digit;
            if (!empty($letters)) {
                //print("<br>\nletters = ");
                //print_r($letters);
                //print("<br>\nused = ");
                //print_r($used);
                $sub_dictionary = $this->generate_dictionary($letters, $used);

                foreach ($sub_dictionary as $sub_res) {
                    //print("<br>\nsub_res = ");
                    //print_r($sub_res);
                    yield array_merge($res, $sub_res);
                }
            } else {
                yield $res;
            }

        }
    }



    private function filter_user_data() : bool
    {
        $this->user_data = mb_strtoupper($this->user_data);
        $this->user_data = mb_ereg_replace("[^A-ZА-Я+*/=-]","",$this -> user_data );
        if (!preg_match('/^[A-ZА-Я]+(?:\s*[+*\/-]\s*[A-ZА-Я]+)*\s*=\s*[A-ZА-Я]+(?:\s*[+*\/-]\s*[A-ZА-Я]+)*$/', $this->user_data, $matches)){
            return false;
        }
        $this->letters = array_unique(mb_str_split(mb_ereg_replace('[^A-ZА-Я]', "", $this->user_data)));
        return sizeof($this->letters) <= 10;
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
                    <input type="text" class="form-control border-primary" id="rebusText"  name = "rebusText" required value="<?php if (isset($this->user_data)) print($this->user_data);?>">
                    <input type="submit" value="Solve" class="btn btn-primary m-2">
                </div>
            </form>
        <?php

    }
}

new APage(new Rebus());