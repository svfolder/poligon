<?php

namespace core\services\auth;

use core\entities\Auth\AuthItemChild;
use core\forms\auth\AuthItemChildForm;
use core\repositories\auth\AuthItemChildRepository;

class AuthItemChildService
{
    /** @var AuthItemChildRepository authItemChildren */
    private $authItemChildren;

    /**
     * AuthItemChildService constructor
     * @var AuthItemChildRepository
     */
    public function __construct(AuthItemChildRepository $authItemChildren)
    {
        $this->authItemChildren = $authItemChildren;
    }

    public function find($parent, $child): ?AuthItemChild
    {
        return $this->authItemChildren->find($parent, $child);
    }

    public function create(AuthItemChildForm $form): AuthItemChild
    {
        $authItemChild = AuthItemChild::create(
            $form->parent,
            $form->child
        );
        $this->authItemChildren->save($authItemChild);
        return $authItemChild;
    }

    public function edit($parent, $child, AuthItemChildForm $form): void
    {
        $authItemChild = $this->authItemChildren->get($parent, $child);
        $authItemChild->edit(
            $form->parent,
            $form->child
        );
        $this->authItemChildren->save($authItemChild);
    }

    public function remove($parent, $child): void
    {
        $authItemChild = $this->authItemChildren->get($parent, $child);
        $this->authItemChildren->delete($authItemChild);
    }

    public function removeAll(): void
    {
        $this->authItemChildren->deleteAll();
    }

    public function assertIsNotRoot(AuthItemChild $authItemChild): void
    {
        if($authItemChild->isRoot()){
            throw new \DomainException('Unable to manage the root category.');
        }
    }
}
