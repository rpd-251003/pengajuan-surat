<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileApproval extends Model
{
    use HasFactory;

    protected $fillable = ['id_pengajuan', 'nomor_surat', 'file_surat'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan');
    }
}

