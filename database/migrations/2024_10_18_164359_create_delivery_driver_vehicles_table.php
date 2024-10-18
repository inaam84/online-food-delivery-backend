<?php

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
        Schema::create('delivery_driver_vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('delivery_driver_id');
            $table->enum('type', ['BIKE', 'ELECTRIC_BIKE', 'SCOOTER', 'CAR']);
            $table->string('registration_number', 15)->nullable();
            $table->integer('year')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->timestamps();

            $table->foreign('delivery_driver_id')->references('id')->on('delivery_drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_driver_vehicles');
    }
};
