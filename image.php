<?php

include_once "common/content.php";
include_once "common/page.php";
include_once "common/db_helper.php";

class image extends \common\AContent {
    public function __construct()
    {
        parent::__construct();
        $this->is_opened = true;
    }

    public function create_content(){
        $currentDate = new DateTime(date("Y-m-d"));

        $db = \common\DbHelper::getInstance();
        $products = $db->getAllProducts();
        foreach ($products as $product){
            $discount = 0;
            $sales = $db->getSalesForProduct($product['id']);
            foreach ($sales as $sale){
                $actionStart = new DateTime($sale['start_time']);
                $actionEnd = new DateTime($sale['end_time']);
                $discount = $currentDate >= $actionStart && $currentDate <= $actionEnd ? $sale['discount'] : 0;
                break;
            }
            print '<img src="img.php?d='.$discount.'&src=img/'.urlencode($product['img']).'" alt="Изображение" width="500">';
            print '<br>';
        }

    }

}

new \common\APage(new image());
