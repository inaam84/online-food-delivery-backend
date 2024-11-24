<?php

use App\Enums\FoodDiscountType;
use App\Models\Food;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Food::class, 'food_id')->constrained()->onDelete('cascade');
            $table->enum('discount_type', array_column(FoodDiscountType::cases(), 'value'))
                ->default(FoodDiscountType::FIXED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_discounts');
    }
};
