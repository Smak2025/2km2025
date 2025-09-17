<?php

namespace common;
class Menu
{
    private static string $menu_file = "common/menu.json";
    private static ?array $menu = null;
    private static function load(){
        $menu = [];
        try{
            $menu_data = file_get_contents(self::$menu_file);
            $menu = json_decode($menu_data, true);
        } catch (\Exception){
        }
        self::$menu = $menu;
    }

    public static function get_menu_data(){
        if (self::$menu === null) self::load();
        return self::$menu;
    }

    public static function get_title()
    {
        $menu = Menu::get_menu_data();
        foreach ($menu as $item) {
            if (strtolower($_SERVER['SCRIPT_NAME']) == strtolower($item["href"])) {
                return $item["title"];
            }
        }
        return "";
    }
}