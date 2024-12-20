<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;

    protected $table = 'food_categories';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
