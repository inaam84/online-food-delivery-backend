<?php

use App\Models\FoodCategory;
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
        Schema::create('food_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        FoodCategory::insert(
            [
                [
                    'name' => 'Indian Food',
                    'description' => 'A traditional Indian food',
                ],
                [
                    'name' => 'Thai Food',
                    'description' => 'A traditional Thai food',
                ],
            ]

        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_categories');
    }
};
