<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaprodiTahunan extends Model
{
    use HasFactory;

    protected $fillable = ['tahun_angkatan', 'prodi_id', 'user_id'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
