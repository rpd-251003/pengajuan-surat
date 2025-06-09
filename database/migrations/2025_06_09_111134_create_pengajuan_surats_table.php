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
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');
            $table->string('tahun_angkatan');
            $table->string('prodi_id');
            $table->string('fakultas_id');

            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->onDelete('cascade');

            $table->text('keterangan')->nullable();
            $table->foreignId('approved_by_dosen_pa')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_dosen_pa')->nullable();
            $table->foreignId('approved_by_kaprodi')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_kaprodi')->nullable();
            $table->foreignId('approved_by_wadek1')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_wadek1')->nullable();
            $table->foreignId('approved_by_staff_tu')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at_staff_tu')->nullable();
            $table->enum('status', ['diajukan', 'diproses', 'disetujui', 'ditolak'])->default('diajukan');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};
