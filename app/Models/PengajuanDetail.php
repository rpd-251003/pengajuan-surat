<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_surat_id',
        'field_name',
        'field_value',
        'field_type'
    ];

    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class);
    }

    /**
     * Get detail as key-value array
     */
    public static function getDetailsByPengajuanId($pengajuanId)
    {
        return self::where('pengajuan_surat_id', $pengajuanId)
            ->pluck('field_value', 'field_name')
            ->toArray();
    }

    /**
     * Store multiple details
     */
    public static function storeDetails($pengajuanId, $details)
    {
        foreach ($details as $fieldName => $fieldValue) {
            self::updateOrCreate(
                [
                    'pengajuan_surat_id' => $pengajuanId,
                    'field_name' => $fieldName
                ],
                [
                    'field_value' => is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue,
                    'field_type' => is_array($fieldValue) ? 'array' : 'text'
                ]
            );
        }
    }
}
