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
        $stud = $this->get_stud_info();
        $del_studs = $this->get_studs_to_delete();
        try {
            $this->helper = DbHelper::getInstance();
            $this->helper->createStudentTable();
            //$this->helper->addStudent('Иван', 'Иванов', "2001-05-30", '05-410');
            if($stud != null){
                $this->helper->addStudent($stud);
            }
            if ($del_studs != null && count($del_studs) > 0) {
                foreach ($del_studs as $stud_id) {
                    $this->helper->deleteStudent($stud_id);
                }
            }
        } catch (\PDOException $e) {
            $this->conn_error = $e->getMessage();
        }
    }

    public function get_studs_to_delete(): ?array{
        if (isset($_POST['delete'])) {
            $ids = [];
            foreach ($_POST as $key=>$value) {
                $id = preg_replace("/^row_(\\d+)$/", "$1", $key);
                if ($key != $id && $id != null) {
                    $ids[] = $id;
                }
            }
            return $ids;
        }
        return null;
    }
    public function get_stud_info():?array {
        if(isset($_POST['add'])){
            $stud = [];
            if(isset($_POST['first_name']) && isset($_POST['last_name'])&& isset($_POST['birth_date']) && isset($_POST['group'])){
                $stud['first_name'] =htmlspecialchars($_POST['first_name']);
                $stud['last_name'] = htmlspecialchars($_POST['last_name']);
                $stud['birth_date'] = htmlspecialchars($_POST['birth_date']);
                $stud['group'] = htmlspecialchars($_POST['group']);
                return $stud;
            }
        }
        return null;
    }
    public function create_content()
    {
        if ($this->conn_error != null) {
            print "<div class='alert alert-danger'>$this->conn_error</div>";
        }
        ?>
        <form method="POST" action="db_test.php">
            <label for="first_name" >First Name</label>
            <input name="first_name" type="text" id="first_name">
            <br>
            <label for="last_name" >Last Name</label>
            <input name="last_name" type="text" id="last_name">
            <br>
            <label for="birth_date" >Birth Date</label>
            <input name="birth_date" type="date" id="birth_date">
            <br>
            <label for="group" >Group Num</label>
            <input name="group" type="text" id="group">
            <input type="hidden" name="add" value="1">
            <br>
            <input type="submit" value="Add Student">
        </form>
        <?php
        $studs = $this->helper->getAllStudents();
        print '<form method="POST" action="db_test.php">';
        print '<table class="table table-striped">';
        $heading = true;
        foreach ($studs as $stud) {
            if ($heading) {
                print '<tr>';
                $heading = false;
                print '<td>&nbsp;</td>';
                foreach ($stud as $k => $v) {
                    print '<td class="text-center text-primary border border-1">';
                    print $k;
                    print '</td>';
                }
                print "</tr>\n";
            }
            print '<tr>';
            print '<td><input type="checkbox" name="row_'.$stud['id'].'"></td>';
            foreach ($stud as $k => $v) {
                print '<td class="border border-1">';
                print "<a href='db_test_2.php?stud_id={$stud['id']}'>$v</a>";
                print '</td>';
            }
            print "</tr>\n";
        }
        print '</table>';
        print '<input type="hidden" value="1" name="delete">';
        print '<input type="submit" value="Delete Selected Students">';
        print '</form>';
    }
}

new \common\APage(new DbTest());