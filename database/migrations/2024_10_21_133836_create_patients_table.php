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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->date('dob')->nullable();
            $table->char('gender', 1)->checkIn(['M', 'F']);
            $table->string('identification_no')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->text('note')->nullable();
            $table->string('account_id')->nullable();
            $table->uuid('affiliation_id');
            $table->timestamps();

            $table->foreign('affiliation_id')->references('id')->on('affiliations')->onDelete('cascade')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
