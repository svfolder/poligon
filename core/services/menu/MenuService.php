<?php

namespace core\services\menu;

use core\entities\Menu\Menu;
use core\forms\menu\MenuForm;
use core\repositories\menu\MenuRepository;

class MenuService
{
    /** @var MenuRepository */
    protected $menus;

    /**
     * MenuService constructor
     * @var MenuRepository
     */
    public function __construct(MenuRepository $menus)
    {
        $this->menus = $menus;
    }

    public function find($id): ?Menu
    {
        return $this->menus->find($id);
    }

    /**
     * @return Menu[]
     */
    public function findAll(): array
    {
        return $this->menus->findAll();
    }

    public function create(MenuForm $form): Menu
    {
        $menu = Menu::create(
            $form->name,
            $form->alias,
            $form->status
        );
        $this->menus->save($menu);
        return $menu;
    }

    public function edit($id, MenuForm $form): void
    {
        $menu = $this->menus->get($id);
        $menu->edit(
            $form->name,
            $form->alias,
            $form->status
        );
        $this->menus->save($menu);
    }

    public function remove($id): void
    {
        $menu = $this->menus->get($id);
        $this->menus->delete($menu);
    }

    public function removeAll(): void
    {
        $this->menus->deleteAll();
    }

    public function getRepository(): MenuRepository
    {
        return $this->menus;
    }
}
