<?php

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
        Schema::create('food_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Food::class, 'food_id')->constrained()->onDelete('cascade');
            $table->string('tag', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_tags');
    }
};
