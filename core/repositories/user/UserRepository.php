<?php

namespace core\repositories\user;

use core\entities\User\User;

class UserRepository
{
    use UserRepositoryTrait;

    public function get($id): User
    {
        if (!$user = User::findOne($id)) {
            throw new \core\repositories\NotFoundException('User is not found.');
        }
        return $user;
    }

    public function find($id): ?User
    {
        return User::findOne($id);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException("Saving error. {$user->plainErrors()}");
        }
    }

    public function delete(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        User::deleteAll();
    }
}
