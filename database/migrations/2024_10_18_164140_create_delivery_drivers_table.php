<?php

use App\Enums\DeliveryDriverRegistrationStatus;
use App\Enums\DeliveryDriverStatus;
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
        Schema::create('delivery_drivers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 50);
            $table->string('surname', 50);
            $table->string('email')->unique();
            $table->string('landline_phone', 50)->nullable();
            $table->string('mobile_phone', 50)->nullable();
            $table->string('address_line_1', 70);
            $table->string('town', 70)->nullable();
            $table->string('city', 70)->nullable();
            $table->string('county', 70);
            $table->string('postcode', 15);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('registration_status', array_column(DeliveryDriverRegistrationStatus::cases(), 'value'))
                ->default(DeliveryDriverRegistrationStatus::PENDING_APPROVAL->value);
            $table->enum('status', array_column(DeliveryDriverStatus::cases(), 'value'))
                ->default(DeliveryDriverStatus::WORKING->value);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_drivers');
    }
};
