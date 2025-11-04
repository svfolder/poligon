<?php

namespace core\repositories\auth;

use core\entities\Auth\AuthItemChild;

class AuthItemChildRepository
{
    public function get($parent, $child): AuthItemChild
    {
        if (!$authItemChild = AuthItemChild::findOne(['parent' => $parent, 'child' => $child])) {
            throw new \core\repositories\NotFoundException('AuthItemChild is not found.');
        }
        return $authItemChild;
    }

    public function find($parent, $child): ?AuthItemChild
    {
        return AuthItemChild::findOne(['parent' => $parent, 'child' => $child]);
    }

    public function save(AuthItemChild $authItemChild): void
    {
        if (!$authItemChild->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function delete(AuthItemChild $authItemChild): void
    {
        if (!$authItemChild->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        AuthItemChild::deleteAll();
    }
}
