<?php

namespace core\services\auth;

use core\entities\Auth\AuthItem;
use core\forms\auth\AuthItemForm;
use core\repositories\auth\AuthItemRepository;

class AuthItemService
{
    /** @var AuthItemRepository authItems */
    private $authItems;

    /**
     * AuthItemService constructor
     * @var AuthItemRepository
     */
    public function __construct(AuthItemRepository $authItems)
    {
        $this->authItems = $authItems;
    }

    public function find($name): ?AuthItem
    {
        return $this->authItems->find($name);
    }

    public function create(AuthItemForm $form): AuthItem
    {
        $authItem = AuthItem::create(
            $form->name,
            $form->type,
            $form->description,
            $form->rule_name,
            $form->data,
            $form->created_at,
            $form->updated_at
        );
        $this->authItems->save($authItem);
        return $authItem;
    }

    public function edit($name, AuthItemForm $form): void
    {
        $authItem = $this->authItems->get($name);
        $authItem->edit(
            $form->name,
            $form->type,
            $form->description,
            $form->rule_name,
            $form->data,
            $form->created_at,
            $form->updated_at
        );
        $this->authItems->save($authItem);
    }

    public function remove($name): void
    {
        $authItem = $this->authItems->get($name);
        $this->authItems->delete($authItem);
    }

    public function removeAll(): void
    {
        $this->authItems->deleteAll();
    }

    public function assertIsNotRoot(AuthItem $authItem): void
    {
        if($authItem->isRoot()){
            throw new \DomainException('Unable to manage the root category.');
        }
    }
}
