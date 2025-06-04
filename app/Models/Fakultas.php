<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = ['nama'];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }
}
