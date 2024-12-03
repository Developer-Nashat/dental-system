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
        Schema::create('visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->BigInteger('patient_id');
            $table->uuid('dentist_id');
            $table->BigInteger('status_id');
            $table->date('visit_date')->index();
            $table->time('visit_time')->nullable();
            $table->boolean('is_done')->default(false);

            // Make sure that the foreign keys are not null
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade')->constrained();
            $table->foreign('dentist_id')->references('id')->on('users')->onDelete('cascade')->constrained();
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
