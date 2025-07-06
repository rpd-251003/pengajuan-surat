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
        Schema::create('pengajuan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surats')->onDelete('cascade');
            $table->string('field_name'); // nama field dinamis
            $table->text('field_value')->nullable(); // nilai field
            $table->string('field_type')->default('text'); // text, select, checkbox, file, etc
            $table->timestamps();

            $table->index(['pengajuan_surat_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_details');
    }
};
