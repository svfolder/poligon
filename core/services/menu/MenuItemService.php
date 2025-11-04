<?php

namespace core\services\menu;

use core\entities\Menu\MenuItem;
use core\forms\menu\MenuItemForm;
use core\repositories\menu\MenuItemRepository;

class MenuItemService
{
    /** @var MenuItemRepository */
    protected $menuItems;

    /**
     * MenuItemService constructor
     * @var MenuItemRepository
     */
    public function __construct(MenuItemRepository $menuItems)
    {
        $this->menuItems = $menuItems;
    }

    public function find($id): ?MenuItem
    {
        return $this->menuItems->find($id);
    }

    /**
     * @return MenuItem[]
     */
    public function findAll(): array
    {
        return $this->menuItems->findAll();
    }

    public function create(MenuItemForm $form): MenuItem
    {
        $menuItem = MenuItem::create(
            $form->parent_id,
            $form->menu_id,
            $form->name,
            $form->link,
            $form->sort,
            $form->status
        );
        $this->menuItems->save($menuItem);
        return $menuItem;
    }

    public function edit($id, MenuItemForm $form): void
    {
        $menuItem = $this->menuItems->get($id);
        $menuItem->edit(
            $form->parent_id,
            $form->menu_id,
            $form->name,
            $form->link,
            $form->sort,
            $form->status
        );
        $this->menuItems->save($menuItem);
    }

    public function remove($id): void
    {
        $menuItem = $this->menuItems->get($id);
        $this->menuItems->delete($menuItem);
    }

    public function removeAll(): void
    {
        $this->menuItems->deleteAll();
    }

    public function getRepository(): MenuItemRepository
    {
        return $this->menuItems;
    }
}
