<?php

namespace App\Interfaces;

interface VendorFoodRepositoryInterface
{
    public function getAllFoods();

    public function getFoodById($foodId);

    public function deleteFood($foodId);

    public function createFood(array $foodDetails);

    public function updateFood($foodId, array $newDetails);
}
