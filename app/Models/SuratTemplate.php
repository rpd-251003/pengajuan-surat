<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'nama_template',
        'template_content',
        'css_styles',
        'variables',
        'is_active',
        'header_image',
        'footer_text',
        'orientation',
        'paper_size'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    /**
     * Generate PDF content by replacing placeholders with actual data
     */
    public function generateContent($data)
    {
        $content = $this->template_content;

        // Replace placeholders dengan data aktual
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }

    /**
     * Get default variables for this template
     */
    public function getDefaultVariables()
    {
        return $this->variables ?? [];
    }

    /**
     * Get template with CSS
     */
    public function getFullTemplate()
    {
        $css = $this->css_styles ? "<style>{$this->css_styles}</style>" : '';
        return $css . $this->template_content;
    }
}
