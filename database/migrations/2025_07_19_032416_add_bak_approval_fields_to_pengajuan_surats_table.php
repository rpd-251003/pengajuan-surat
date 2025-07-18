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
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by_bak')->nullable()->comment('User ID who approved as BAK');
            $table->timestamp('approved_at_bak')->nullable()->comment('BAK approval timestamp');
            $table->json('current_approval_flow')->nullable()->comment('Current approval flow for this pengajuan');
            $table->string('current_step')->nullable()->comment('Current approval step');
            $table->json('approval_history')->nullable()->comment('History of approvals');
            
            $table->foreign('approved_by_bak')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropForeign(['approved_by_bak']);
            $table->dropColumn(['approved_by_bak', 'approved_at_bak', 'current_approval_flow', 'current_step', 'approval_history']);
        });
    }
};
