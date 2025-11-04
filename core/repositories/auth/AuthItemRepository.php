<?php

namespace core\repositories\auth;

use core\entities\Auth\AuthItem;

class AuthItemRepository
{
    public function get($name): AuthItem
    {
        if (!$authItem = AuthItem::findOne($name)) {
            throw new \core\repositories\NotFoundException('AuthItem is not found.');
        }
        return $authItem;
    }

    public function find($name): ?AuthItem
    {
        return AuthItem::findOne($name);
    }

    public function findByName($name): ?AuthItem
    {
        return AuthItem::findOne(['name' => $name]);
    }

    public function save(AuthItem $authItem): void
    {
        if (!$authItem->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function delete(AuthItem $authItem): void
    {
        if (!$authItem->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        AuthItem::deleteAll();
    }
}
