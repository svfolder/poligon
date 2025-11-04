<?php

namespace core\services\user;

use core\entities\User\User;
use core\forms\user\UserForm;
use core\repositories\user\UserRepository;
use core\services\RoleManager;
use core\services\TransactionManager;

class UserService
{
    use UserServiceTrait;

    /** @var UserRepository */
    protected $users;

    /** @var RoleManager */
    protected $roles;

    /** @var TransactionManager */
    protected $transaction;

    /**
     * UserService constructor
     * @var UserRepository
     * @var RoleManager
     * @var TransactionManager
     */
    public function __construct(UserRepository $users, RoleManager $roles, TransactionManager $transaction)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function find($id): ?User
    {
        return $this->users->find($id);
    }

    public function create(UserForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->first_name,
            $form->last_name,
            $form->image,
            $form->email,
            $form->is_banned,
        );

        $this->transaction->wrap(function () use ($form, $user) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });
        return $user;
    }

    public function edit($id, UserForm $form): void
    {
        $user = $this->users->get($id);
        $user->edit(
            $form->username,
            $form->first_name,
            $form->last_name,
            $form->image,
            $form->email,
            $form->is_banned,
        );

        $this->transaction->wrap(function () use ($form, $user) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function remove($id): void
    {
        $user = $this->users->get($id);
        $this->users->delete($user);
    }

    public function removeAll(): void
    {
        $this->users->deleteAll();
    }
}
