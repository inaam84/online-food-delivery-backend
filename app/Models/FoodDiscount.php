<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDiscount extends Model
{
    use HasFactory;

    protected $table = 'food_discounts';

    protected $fillable = [
        'id',
        'food_id',
        'discount_type',
        'value',
        'start_date',
        'end_date',
    ];
}
