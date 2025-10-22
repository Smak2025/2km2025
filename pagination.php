<?php

include_once "common/content.php";
include_once "common/page.php";
class Pagination extends \common\AContent {
    private int $max_size = 100000;
    private string $upload_dir='upload/' ;
    private string $last_uploaded_file = 'upload/filename';
    private ?string $upload_url = null;
    private ?string $error_message = null;
    private int $row_count = 10;
    public function __construct(){
        parent::__construct();
        try {
            $this->upload_url = $this->upload_file();
            if (isset($this->upload_url))
            {
                $f=fopen($this->last_uploaded_file,'w');
                fwrite($f,$this->upload_url);
                fclose($f);
            }
            else {
                $f = fopen($this->last_uploaded_file,'r');
                $this->upload_url = fgets($f);
                fclose($f);
            }
        } catch (Exception $ex) {
            $this->error_message = $ex->getMessage();
        }

    }
    public function create_content(): void
    {
        if(isset($this->error_message)){
           echo"<div class='alert alert-danger'> $this->error_message </div>";
        }
        ?>
        <div>
            <form action="pagination.php" method="post" enctype="multipart/form-data">
                <label for="user_file" class='form-label text-primary'>Укажите файл для загрузки: </label>
                <input type="file" class='form-control' name='user_file' id="user_file">
                <br>
                <input type="submit" value="Отправить" class="btn btn-primary">
            </form>
        </div>
        <?php
        $this->show_file_content();
        if(isset($this->error_message)){
            echo"<div class='alert alert-danger'> $this->error_message </div>";
        }
    }

    private function show_file_content(): void{
        try {
            $generator = $this->file_row();
            $i = -1;
            if (isset($_GET['p']) && is_numeric($_GET['p'])) {
                $page = (int)$_GET['p'];
            } else {
                $page = 1;
            }

            $shift = ($page - 1) * $this->row_count + 1;
            print('<table>');
            foreach ($generator as $row){
                $i++;
                if ($i < $shift && $i!=0) continue;
                print('<tr>');
                foreach ($row as $col){
                    if ($i == 0){
                        $class = 'border border-primary m-1 p-2 text-primary fw-bold text-center';
                    } else {
                        $class = 'border border-secondary m-1 p-2';
                    }
                    print('<td class="'.$class.'">'.$col.'</td>');
                }
                print('</tr>');
                if ($i > 0 && $i % $this->row_count == 0){
                    break;
                }
            }
            print('</table>');
        } catch (Exception $ex) {
            $this->error_message = $ex->getMessage();
        }
    }
    /**
     * @return void
     * @throws Exception
     */
    private function upload_file(): ?string
    {
        if (isset($_FILES['user_file'])) {
            if ($_FILES['user_file']['size'] > $this->max_size) {
                throw new Exception('File is too large!');
            }
            $upload_file = $this->upload_dir . basename($_FILES['user_file']['name']);
            if (!@move_uploaded_file($_FILES['user_file']['tmp_name'], $upload_file)) {
                throw new Exception('Upload failed!');
            }
            return $upload_file;
        }
        return null;
    }

    /**
     * @throws Exception
     */
    private function file_row(): Generator{
        if (!isset($this->upload_url)) throw new Exception("Отсутсвует файл для открытия");
        $file = @fopen($this->upload_url, 'r');
        while (!feof($file)) {
            $result = fgetcsv($file, separator: ';');
            if ($result) {
                yield $result;
            }
        }
        fclose($file);
    }
}

new \common\APage(new Pagination());