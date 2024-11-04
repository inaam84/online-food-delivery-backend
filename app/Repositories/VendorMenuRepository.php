<?php

namespace App\Repositories;

use App\Interfaces\VendorMenuRepositoryInterface;
use App\Models\Menu;

class VendorMenuRepository implements VendorMenuRepositoryInterface
{
    public function getAllMenus()
    {
        return Menu::all();
    }

    public function getMenuById($menuId)
    {
        Menu::findOrFail($menuId);
    }

    public function createMenu(array $menuDetails)
    {
        return Menu::create($menuDetails);
    }

    public function deleteMenu($menuId)
    {
        Menu::destroy($menuId);
    }

    public function updateMenu($menuId, array $newDetails)
    {
        return Menu::whereId($menuId)
            ->update($newDetails);
    }
}
