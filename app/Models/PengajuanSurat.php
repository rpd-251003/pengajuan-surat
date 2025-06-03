<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_surat',
        'keperluan',
        'status',
        'catatan',
        'tanggal_pengajuan',
    ];

    /**
     * Relasi ke User (mahasiswa yang mengajukan surat)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        // Relasi ke mahasiswa (user)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke tahun akademik
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
