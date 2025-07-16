<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function fields()
    {
        return $this->hasMany(JenisSuratField::class)->orderBy('sort_order');
    }

    // Relasi baru untuk template
    public function templates()
    {
        return $this->hasMany(SuratTemplate::class);
    }

    // Mendapatkan template aktif
    public function activeTemplate()
    {
        return $this->hasOne(SuratTemplate::class)->where('is_active', true);
    }

    /**
     * Get fields for dynamic form
     */
    public function getFormFields()
    {
        return $this->fields()->get();
    }

    /**
     * Check if jenis surat has template
     */
    public function hasTemplate()
    {
        return $this->templates()->where('is_active', true)->exists();
    }

    /**
     * Get active template
     */
    public function getActiveTemplate()
    {
        return $this->activeTemplate;
    }
}
