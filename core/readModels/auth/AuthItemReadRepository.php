<?php

namespace core\readModels\auth;

use core\entities\Auth\AuthItem;

class AuthItemReadRepository
{
    public function count(): int
    {
        return AuthItem::find()->count();
    }

    public function getAll($name): array
    {
        return AuthItem::findAll($name);
    }

    public function getAllByRange($offset, $limit): array
    {
        return AuthItem::find()->offset($offset)->limit($limit)->all();
    }

    public function find($name): ?AuthItem
    {
        return AuthItem::find()->andWhere($name)->one();
    }
}
