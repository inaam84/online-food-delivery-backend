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
        Schema::create('vendors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 50);
            $table->string('surname', 50);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('business_name', 70);
            $table->string('trading_name', 70)->nullable();
            $table->string('address_line_1', 70);
            $table->string('town', 70)->nullable();
            $table->string('city', 70)->nullable();
            $table->string('county', 70)->nullable();
            $table->string('postcode', 15);
            $table->string('landline_phone', 50)->nullable();
            $table->string('mobile_phone', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
