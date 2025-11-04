<?php

namespace core\readModels\menu;

use core\entities\Menu\MenuItem;

class MenuItemReadRepository
{
    public function count(): int
    {
        return MenuItem::find()->active()->count();
    }

    public function getAll($id): array
    {
        return MenuItem::findAll($id);
    }

    public function getAllByRange($offset, $limit): array
    {
        return MenuItem::find()->active()->orderBy(['id' => SORT_ASC])->offset($offset)->limit($limit)->all();
    }

    public function find($id): ?MenuItem
    {
        return MenuItem::find()->active()->andWhere($id)->one();
    }
}
