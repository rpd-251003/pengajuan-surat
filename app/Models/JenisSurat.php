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

    /**
     * Get fields for dynamic form
     */
    public function getFormFields()
    {
        return $this->fields()->get();
    }

}
