<?php

namespace App\Repositories;

use App\Interfaces\FoodRepositoryInterface;
use App\Models\Food;

class FoodRepository implements FoodRepositoryInterface
{
    public function getAllFoods()
    {
        return Food::all();
    }

    public function getFoodById($foodId)
    {
        return Food::find($foodId);
    }

    public function createFood(array $foodDetails)
    {
        return Food::create($foodDetails);
    }

    public function deleteFood($foodId)
    {
        $food = $this->getFoodById($foodId);

        return ! is_null($food) ? $food->delete() : 0;
    }

    public function updateFood($foodId, array $newDetails)
    {
        $food = $this->getFoodById($foodId);
        if ($food) {
            $food->update($newDetails);
        }

        return $food;
    }
}
