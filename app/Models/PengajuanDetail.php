<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            ->get()
            ->mapWithKeys(function ($detail) {
                if ($detail->field_type === 'file') {
                    $fileInfo = json_decode($detail->field_value, true);
                    return [$detail->field_name => $fileInfo];
                } else if ($detail->field_type === 'array') {
                    $value = json_decode($detail->field_value, true);
                    return [$detail->field_name => $value ?: $detail->field_value];
                } else {
                    return [$detail->field_name => $detail->field_value];
                }
            })
            ->toArray();
    }

    /**
     * Store multiple details with file support
     */
    public static function storeDetails($pengajuanId, $details, $files = [])
    {
        foreach ($details as $fieldName => $fieldValue) {
            // Skip empty values
            if (empty($fieldValue) && $fieldValue !== '0') {
                continue;
            }

            // Handle file upload
            if (isset($files[$fieldName]) && $files[$fieldName]->isValid()) {
                $file = $files[$fieldName];

                // Generate filename
                $filename = time() . '_' . $fieldName . '_' . $file->getClientOriginalName();

                // Store file
                $filePath = $file->storeAs('pengajuan_files/' . $pengajuanId, $filename, 'public');

                // Store file info as JSON
                $fileInfo = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'size' => $file->getSize(),
                    'uploaded_at' => now()->toDateTimeString()
                ];

                self::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $pengajuanId,
                        'field_name' => $fieldName
                    ],
                    [
                        'field_value' => json_encode($fileInfo),
                        'field_type' => 'file'
                    ]
                );

            } else {
                // Handle regular fields
                if (is_array($fieldValue)) {
                    $value = json_encode($fieldValue);
                    $type = 'array';
                } else {
                    $value = $fieldValue;
                    $type = 'text';
                }

                self::updateOrCreate(
                    [
                        'pengajuan_surat_id' => $pengajuanId,
                        'field_name' => $fieldName
                    ],
                    [
                        'field_value' => $value,
                        'field_type' => $type
                    ]
                );
            }
        }
    }
}
