<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_identifikasi',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role or any of the given roles
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        // If roles is a string, convert to array
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // Check if user's role is in the given roles array
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has any of the admin roles
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(['admin', 'tu', 'wadek1', 'kaprodi']);
    }

    /**
     * Check if user is a mahasiswa
     *
     * @return bool
     */
    public function isMahasiswa()
    {
        return $this->hasRole('mahasiswa');
    }

    /**
     * Check if user is a dosen
     *
     * @return bool
     */
    public function isDosen()
    {
        return $this->hasRole('dosen');
    }

    /**
     * Check if user is a kaprodi
     *
     * @return bool
     */
    public function isKaprodi()
    {
        return $this->hasRole('kaprodi');
    }

    /**
     * Check if user is wadek1
     *
     * @return bool
     */
    public function isWadek1()
    {
        return $this->hasRole('wadek1');
    }

    /**
     * Check if user is staff TU
     *
     * @return bool
     */
    public function isStaffTU()
    {
        return $this->hasRole('tu');
    }

    /**
     * Check if user can approve as Dosen PA
     * This checks if user is in DosenPaTahunan table
     *
     * @param int $angkatan
     * @param int $prodiId
     * @return bool
     */
    public function canApproveAsDosenPA($angkatan = null, $prodiId = null)
    {
        if (!$this->hasRole(['dosen', 'kaprodi'])) {
            return false;
        }

        // If no specific angkatan/prodi provided, just check if user is dosen
        if (!$angkatan || !$prodiId) {
            return true;
        }

        // Check if user is assigned as Dosen PA for specific angkatan and prodi
        return DB::table('dosen_pa_tahunans')
            ->where('user_id', $this->id)
            ->where('tahun_angkatan', $angkatan)
            ->where('prodi_id', $prodiId)
            ->exists();
    }

    /**
     * Check if user can approve as Kaprodi
     * This checks if user is in KaprodiTahunan table
     *
     * @param int $angkatan
     * @param int $prodiId
     * @return bool
     */
    public function canApproveAsKaprodi($angkatan = null, $prodiId = null)
    {
        if (!$this->hasRole('kaprodi')) {
            return false;
        }

        // If no specific angkatan/prodi provided, just check if user is kaprodi
        if (!$angkatan || !$prodiId) {
            return true;
        }

        // Check if user is assigned as Kaprodi for specific angkatan and prodi
        return DB::table('kaprodi_tahunans')
            ->where('user_id', $this->id)
            ->where('tahun_angkatan', $angkatan)
            ->where('prodi_id', $prodiId)
            ->exists();
    }

    /**
     * Check if user is both Dosen PA and Kaprodi for the same pengajuan
     * This is useful for users who have dual roles
     *
     * @param int $angkatan
     * @param int $prodiId
     * @return bool
     */
    public function canApproveAsDosenPAAndKaprodi($angkatan, $prodiId)
    {
        return $this->canApproveAsDosenPA($angkatan, $prodiId) &&
               $this->canApproveAsKaprodi($angkatan, $prodiId);
    }

    /**
     * Get user's role display name
     *
     * @return string
     */
    public function getRoleDisplayName()
    {
        $roleNames = [
            'admin' => 'Administrator',
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'kaprodi' => 'Kepala Program Studi',
            'wadek1' => 'Wakil Dekan 1',
            'tu' => 'Tata Usaha',
            'dosen_pa' => 'Dosen Pembimbing Akademik'
        ];

        return $roleNames[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get user's role badge CSS class
     *
     * @return string
     */
    public function getRoleBadgeClass()
    {
        $badgeClasses = [
            'admin' => 'bg-primary',
            'mahasiswa' => 'bg-success',
            'dosen' => 'bg-info',
            'kaprodi' => 'bg-warning',
            'wadek1' => 'bg-secondary',
            'tu' => 'bg-dark',
            'dosen_pa' => 'bg-info'
        ];

        return $badgeClasses[$this->role] ?? 'bg-secondary';
    }

    /**
     * Relationships
     */

    /**
     * Get mahasiswa data if user is mahasiswa
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /**
     * Get dosen PA assignments
     */
    public function dosenPaAssignments()
    {
        return $this->hasMany(DosenPaTahunan::class);
    }

    /**
     * Get kaprodi assignments
     */
    public function kaprodiAssignments()
    {
        return $this->hasMany(KaprodiTahunan::class);
    }

    /**
     * Get pengajuan surats where user is dosen PA
     */
    public function pengajuanAsDosenPA()
    {
        return $this->hasMany(PengajuanSurat::class, 'approved_by_dosen_pa');
    }

    /**
     * Get pengajuan surats where user is kaprodi
     */
    public function pengajuanAsKaprodi()
    {
        return $this->hasMany(PengajuanSurat::class, 'approved_by_kaprodi');
    }

    /**
     * Get pengajuan surats where user is wadek1
     */
    public function pengajuanAsWadek1()
    {
        return $this->hasMany(PengajuanSurat::class, 'approved_by_wadek1');
    }

    /**
     * Get pengajuan surats where user is staff TU
     */
    public function pengajuanAsStaffTU()
    {
        return $this->hasMany(PengajuanSurat::class, 'approved_by_staff_tu');
    }
}
