<?php


namespace core\helpers;


class MenuHelper
{
    public static function merge($items, $menus){
        foreach ($menus as $menu){
            $items[] = $menu;
        }
        return $items;
    }
}