<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'approval_flow', 'requires_number_generation'];

    protected $casts = [
        'approval_flow' => 'array',
        'requires_number_generation' => 'boolean'
    ];

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

    /**
     * Get approval flow or return default
     */
    public function getApprovalFlow()
    {
        return $this->approval_flow ?? $this->getDefaultApprovalFlow();
    }

    /**
     * Get default approval flow
     */
    private function getDefaultApprovalFlow()
    {
        return [
            'dosen_pa',
            'kaprodi', 
            'wadek1',
            'tu'
        ];
    }

    /**
     * Get available approval roles
     */
    public static function getAvailableApprovalRoles()
    {
        return [
            'dosen_pa' => 'Dosen PA',
            'kaprodi' => 'Kaprodi',
            'wadek1' => 'Wadek 1',
            'tu' => 'Tata Usaha',
            'bak' => 'Biro Akademik Kemahasiswaan'
        ];
    }

    /**
     * Check if this jenis surat requires specific approval role
     */
    public function requiresApprovalFrom($role)
    {
        $flow = $this->getApprovalFlow();
        return in_array($role, $flow);
    }

    /**
     * Get the next approval step after current role
     */
    public function getNextApprovalStep($currentRole)
    {
        $flow = $this->getApprovalFlow();
        $currentIndex = array_search($currentRole, $flow);
        
        if ($currentIndex === false || $currentIndex === count($flow) - 1) {
            return null;
        }
        
        return $flow[$currentIndex + 1];
    }

    /**
     * Check if role is the final approval step
     */
    public function isFinalApprovalStep($role)
    {
        $flow = $this->getApprovalFlow();
        return end($flow) === $role;
    }
}
