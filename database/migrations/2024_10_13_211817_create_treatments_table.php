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
        Schema::create('treatments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            $table->bigInteger('service_id');
            $table->bigInteger('problem_id');
            $table->bigInteger('tooth_id');
            $table->timestamps();

            // Make sure that the foreign keys are not null
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade')->constrained();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->constrained();
            $table->foreign('problem_id')->references('id')->on('problem_catalogs')->onDelete('cascade')->constrained();
            $table->foreign('tooth_id')->references('id')->on('teeth')->onDelete('cascade')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
