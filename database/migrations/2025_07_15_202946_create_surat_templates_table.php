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
        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->onDelete('cascade');
            $table->string('nama_template');
            $table->longText('template_content'); // HTML template dengan placeholder
            $table->text('css_styles')->nullable(); // CSS untuk styling
            $table->json('variables')->nullable(); // Available variables dalam template
            $table->boolean('is_active')->default(true);
            $table->string('header_image')->nullable(); // Path ke gambar header
            $table->string('footer_text')->nullable(); // Footer template
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->string('paper_size')->default('A4'); // A4, A5, Letter, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_templates');
    }
};
