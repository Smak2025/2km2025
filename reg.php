<?php

include_once "common/content.php";
include_once "common/page.php";
class Reg extends \common\AContent {
    private $reg_data = null;
    private $errors = null;
    public function __construct()
    {
        parent::__construct();
        $this->is_opened = true;
        if (isset($_POST['reg'])) {
            $res = $this->check_data();
            $this->reg_data = $res[0];
            $this->errors = $res[1];
            if (sizeof($this->errors) == 0) {
                $this->save_user();
            }
        }
    }

    private function check_data():array{
        $reg_data = [];
        $errors = [];
        if(isset($_POST['username'])){
            if(preg_match("/^[a-zA-Z_][a-zA-Z_0-9]{2,24}$/", $_POST['username'])){
                if(!$this->user_exists($_POST['username'])) {
                    $reg_data['username'] = $_POST['username'];
                } else {
                    $errors['username'] = 'Username already exists';
                }
            }
            else{
                $errors['username'] = "Username must contain only letters, numbers, and underscores and should contain from 3 to 25 symbols.";
            }
        }
        else {
            $errors['username'] = "username required";
        }
        if(isset($_POST['password'])){
            if(!(preg_match("/^[a-zA-Z_0-9@$!.]{8,25}$/", $_POST['password'])
                    && preg_match("/\d+/", $_POST['password'])
                    &&preg_match("/[!@.$]+/", $_POST['password'])
                    &&preg_match("/[a-z]+/", $_POST['password'])
                    &&preg_match("/[A-Z]+/", $_POST['password']))
            ){
                $errors['password'] = "Password must contain only letters, numbers, and special symbols @,$,!,. and should contain from 8 to 25 symbols.";
            }
        }
        else {
            $errors['password'] = "Password required";
        }
        if(isset($_POST['password2']) && $_POST['password'] == $_POST['password2']) {
            $reg_data['password'] =password_hash( $_POST['password'], PASSWORD_DEFAULT);

        }
        else{
            $errors['password2'] = "Passwords are not the same";
        }
        return [$reg_data, $errors];
    }
    private function user_exists($username):bool
    {
        $f = fopen("users/users.json", "r");
        while(!feof($f)) {
            $data = fgets($f);
            $user = json_decode($data,true);
            if(isset($user['username']) && $user['username'] == $username) {
                fclose($f);
                return true;
            }
        }
        fclose($f);
        return false;
    }


    private function save_user():void{
        $json_data = json_encode($this->reg_data);
        $f = fopen("users/users.json", "a");
        if ($f !== false) {
            fwrite($f, $json_data);
            fwrite($f, "\n");
            fclose($f);
        }
    }

    public function create_content(): void
    {?>
        <form method="post" action="reg.php">
            <div>
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email">
            </div>
            <div>
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter Name"
                       value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                       value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"
                >
            </div>
            <div>
                <label for="password2" class="form-label">Repeat Password</label>
                <input type="password" id="password2" name="password2" class="form-control" placeholder="Enter password"
                       value="<?php if (isset($_POST['password2'])) echo $_POST['password2']; ?>">
            </div>
            <div>
                <input type="submit" value="Register" class="btn btn-primary">
            </div>
            <input type="hidden" name="reg" value="1">
        </form>
    <?
        if (isset($this->errors) && sizeof($this->errors) > 0) {
            foreach ($this->errors as $error) {
                print "<div class='alert alert-danger'>$error</div>";
            }
        }
    }

}

new \common\APage(new Reg());