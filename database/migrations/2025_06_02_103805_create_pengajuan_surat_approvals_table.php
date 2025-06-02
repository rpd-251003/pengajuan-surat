<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pengajuan_surat_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang memproses
            $table->enum('role', ['dosen_pa', 'kaprodi', 'wadek1', 'tu']);
            $table->enum('status', ['disetujui', 'ditolak', 'diproses'])->default('diproses');
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->nullable(); // kapan diproses
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_approvals');
    }
};
