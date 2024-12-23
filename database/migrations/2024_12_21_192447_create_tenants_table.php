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
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('owner_name');
            $table->string('address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('subscription_plan')->default('Basic');
            $table->date('subscription_expiry')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
