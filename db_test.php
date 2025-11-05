<?php

use common\DbHelper;

include_once "common/content.php";
include_once "common/page.php";
include_once "common/db_helper.php";
class DbTest extends \common\AContent
{
    private ?DbHelper $helper;
    private ?string $conn_error = null;
    public function __construct()
    {
        parent::__construct();
        try {
            $this->helper = DbHelper::getInstance();
            $this->helper->createStudentTable();
            $this->helper->addStudent('Иван', 'Иванов', "2001-05-30", '05-410\');DELETE FROM students;--');
        } catch (\PDOException $e) {
            $this->conn_error = $e->getMessage();
        }
    }

    public function create_content()
    {
        if ($this->conn_error != null) {
            print "<div class='alert alert-danger'>$this->conn_error</div>";
        }
    }
}

new \common\APage(new DbTest());