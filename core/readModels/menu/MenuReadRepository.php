<?php

namespace core\readModels\menu;

use core\entities\Menu\Menu;

class MenuReadRepository
{
    public function count(): int
    {
        return Menu::find()->active()->count();
    }

    public function getAll($id): array
    {
        return Menu::findAll($id);
    }

    public function getAllByRange($offset, $limit): array
    {
        return Menu::find()->active()->orderBy(['id' => SORT_ASC])->offset($offset)->limit($limit)->all();
    }

    public function find($id): ?Menu
    {
        return Menu::find()->active()->andWhere($id)->one();
    }
}
