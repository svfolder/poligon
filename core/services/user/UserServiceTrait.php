<?php


namespace core\services\user;


use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;

trait UserServiceTrait
{

    public function createMinimal(UserCreateForm $form): User
    {
        $user = User::createMinimal(
            $form->username,
            $form->email,
            $form->password,
            $form->first_name,
            $form->last_name,
            $form->is_banned
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });
        return $user;
    }

    public function editMinimal($id, UserEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->editMinimal(
            $form->username,
            $form->email,
            $form->first_name,
            $form->last_name,
            $form->image,
            $form->is_banned
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function assignRole($id, $role): void
    {
        $user = $this->users->get($id);
        $this->roles->assign($user->id, $role);
    }

}