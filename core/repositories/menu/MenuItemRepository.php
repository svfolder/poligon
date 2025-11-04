<?php

namespace core\repositories\menu;

use core\entities\Menu\MenuItem;

class MenuItemRepository
{
    public function get($id): MenuItem
    {
        if (!$menuItem = MenuItem::findOne($id)) {
            throw new \core\repositories\NotFoundException('MenuItem is not found.');
        }
        return $menuItem;
    }

    public function getByName($name): MenuItem
    {
        if (!$menuItem = MenuItem::findOne(['name' => $name])) {
            throw new \core\repositories\NotFoundException('MenuItem is not found.');
        }
        return $menuItem;
    }

    public function find($id): ?MenuItem
    {
        return MenuItem::find()->andWhere(['id' => $id])->one();
    }

    /**
     * @return MenuItem[]
     */
    public function findAll(): array
    {
        return MenuItem::find()->all();
    }

    public function findByName($name): ?MenuItem
    {
        return MenuItem::findOne(['name' => $name]);
    }

    public function save(MenuItem $menuItem): void
    {
        if (!$menuItem->save()) {
            throw new \RuntimeException("Saving error. {$menuItem->plainErrors()}");
        }
    }

    public function delete(MenuItem $menuItem): void
    {
        if (!$menuItem->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        MenuItem::deleteAll();
    }
}
