<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'tahun_angkatan',
        'prodi_id',
        'fakultas_id',
        'jenis_surat_id',
        'keterangan',
        'approved_by_dosen_pa',
        'approved_at_dosen_pa',
        'approved_by_kaprodi',
        'approved_at_kaprodi',
        'approved_by_wadek1',
        'approved_at_wadek1',
        'approved_by_staff_tu',
        'approved_at_staff_tu',
        'status'
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dosenPA()
    {
        return $this->belongsTo(User::class, 'approved_by_dosen_pa');
    }

    public function kaprodi()
    {
        return $this->belongsTo(User::class, 'approved_by_kaprodi');
    }

    public function wadek1()
    {
        return $this->belongsTo(User::class, 'approved_by_wadek1');
    }

    public function staffTU()
    {
        return $this->belongsTo(User::class, 'approved_by_staff_tu');
    }

    public function fileApproval()
    {
        return $this->hasOne(FileApproval::class, 'id_pengajuan');
    }
}
