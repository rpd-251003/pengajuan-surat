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
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->json('approval_flow')->nullable()->comment('Dynamic approval workflow configuration');
            $table->boolean('requires_number_generation')->default(true)->comment('Whether this letter type requires number generation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->dropColumn(['approval_flow', 'requires_number_generation']);
        });
    }
};
