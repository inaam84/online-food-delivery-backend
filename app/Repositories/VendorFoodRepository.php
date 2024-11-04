<?php

namespace App\Repositories;

use App\Interfaces\VendorFoodRepositoryInterface;
use App\Models\Food;

class VendorFoodRepository implements VendorFoodRepositoryInterface
{
    public function getAllFoods()
    {
        return Food::all();
    }

    public function getFoodById($foodId)
    {
        Food::findOrFail($foodId);
    }

    public function createFood(array $foodDetails)
    {
        return Food::create($foodDetails);
    }

    public function deleteFood($foodId)
    {
        Food::destroy($foodId);
    }

    public function updateFood($foodId, array $newDetails)
    {
        return Food::whereId($foodId)
            ->update($newDetails);
    }
}
