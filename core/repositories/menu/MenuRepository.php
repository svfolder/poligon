<?php

namespace core\repositories\menu;

use core\entities\Menu\Menu;

class MenuRepository
{
    public function get($id): Menu
    {
        if (!$menu = Menu::findOne($id)) {
            throw new \core\repositories\NotFoundException('Menu is not found.');
        }
        return $menu;
    }

    public function getByName($name): Menu
    {
        if (!$menu = Menu::findOne(['name' => $name])) {
            throw new \core\repositories\NotFoundException('Menu is not found.');
        }
        return $menu;
    }

    public function find($id): ?Menu
    {
        return Menu::findOne($id);
    }

    /**
     * @return Menu[]
     */
    public function findAll(): array
    {
        return Menu::find()->all();
    }

    public function findByName($name): ?Menu
    {
        return Menu::findOne(['name' => $name]);
    }

    public function save(Menu $menu): void
    {
        if (!$menu->save()) {
            throw new \RuntimeException("Saving error. {$menu->plainErrors()}");
        }
    }

    public function delete(Menu $menu): void
    {
        if (!$menu->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        Menu::deleteAll();
    }
}
