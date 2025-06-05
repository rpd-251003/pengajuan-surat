<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenPaTahunan extends Model
{
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
