<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSuratField extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'placeholder',
        'validation_rules',
        'sort_order'
    ];

    protected $casts = [
        'field_options' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    /**
     * Get validation rules for Laravel validator
     */
    public function getValidationRulesAttribute($value)
    {
        $rules = json_decode($value, true) ?? [];

        if ($this->is_required && !in_array('required', $rules)) {
            array_unshift($rules, 'required');
        }

        return $rules;
    }

    /**
     * Get fields ordered by sort_order
     */
    public static function getFieldsByJenisSurat($jenisSuratId)
    {
        return self::where('jenis_surat_id', $jenisSuratId)
            ->orderBy('sort_order')
            ->get();
    }
}
