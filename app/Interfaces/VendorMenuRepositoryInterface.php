<?php

namespace App\Interfaces;

interface VendorMenuRepositoryInterface
{
    public function getAllMenus();

    public function getMenuById($menuId);

    public function deleteMenu($menuId);

    public function createMenu(array $menuDetails);

    public function updateMenu($menuId, array $newDetails);
}
