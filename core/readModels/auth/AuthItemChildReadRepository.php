<?php

namespace core\readModels\auth;

use core\entities\Auth\AuthItemChild;

class AuthItemChildReadRepository
{
    public function count(): int
    {
        return AuthItemChild::find()->count();
    }

    public function getAll($parent, $child): array
    {
        return AuthItemChild::findAll(['parent' => $parent, 'child' => $child]);
    }

    public function getAllByRange($offset, $limit): array
    {
        return AuthItemChild::find()->offset($offset)->limit($limit)->all();
    }

    public function find($parent, $child): ?AuthItemChild
    {
        return AuthItemChild::find()->andWhere(['parent' => $parent, 'child' => $child])->one();
    }
}
