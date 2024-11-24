<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->hasOne(FoodCategory::class);
    }

    public function tags()
    {
        return $this->hasMany(FoodTag::class);
    }
}
