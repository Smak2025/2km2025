<?php

include_once "common/content.php";
include_once "common/page.php";
class Index extends \common\AContent {


    public function create_title(){
        print "Заголовок страницы index.php";
    }

    public function create_content(){
        print "Это контент первой страницы сайта (index.php).";
    }

}

new \common\APage(new Index());
