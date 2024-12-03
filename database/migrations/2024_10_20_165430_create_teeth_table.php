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
        Schema::create('teeth', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();

            // TODO: Implement a validation rule to ensure that tooth_number is between 1 and 32
            $table->integer('tooth_number')->unsigned()->nullable()->constrained()->between(1, 32);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teeth');
    }
};
