<?php

use App\Enums\FoodStatus;
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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(FoodCategory::class, 'category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->string('image')->nullable(); // Path to the food image
            $table->enum('status', array_column(FoodStatus::cases(), 'value'))
                ->default(FoodStatus::AVAILABLE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
