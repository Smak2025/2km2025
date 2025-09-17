<?php

include_once "common/content.php";
include_once "common/page.php";
class Second extends \common\AContent
{

    public function create_title(){
        print "Заголовок страницы second.php";
    }


    public function create_content(){
        print "Это контент второй страницы сайта (second.php).";
    }


}

new \common\APage(new Second());