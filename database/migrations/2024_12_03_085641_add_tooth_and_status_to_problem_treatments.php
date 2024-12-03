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
        Schema::table('problem_treatments', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('tooth_id');
            $table->enum('status', ['pending', 'in_progress', 'treated'])->default('pending')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problem_treatments', function (Blueprint $table) {
            $table->dropColumn(['notes', 'status']);
        });
    }
};
