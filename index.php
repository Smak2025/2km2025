<?php

include_once "common/content.php";
include_once "common/page.php";
class Index extends \common\AContent {
    public function __construct()
    {
        parent::__construct();
        $this->is_opened = true;
    }
    public function create_content(){
        print "Это контент первой страницы сайта (index.php).";
    }

}

new \common\APage(new Index());
