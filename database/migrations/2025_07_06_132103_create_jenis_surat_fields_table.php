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
        Schema::create('jenis_surat_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->onDelete('cascade');
            $table->string('field_name'); // nama field
            $table->string('field_label'); // label yang ditampilkan
            $table->string('field_type'); // text, email, select, checkbox, file, textarea, number
            $table->text('field_options')->nullable(); // untuk select/checkbox (JSON format)
            $table->boolean('is_required')->default(false);
            $table->string('placeholder')->nullable();
            $table->text('validation_rules')->nullable(); // JSON format
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['jenis_surat_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_surat_fields');
    }
};
