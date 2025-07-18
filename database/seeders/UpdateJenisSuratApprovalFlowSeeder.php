<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateJenisSuratApprovalFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSuratFlows = [
            // Contoh: Surat Keterangan Aktif - hanya perlu Dosen PA dan Kaprodi
            [
                'nama' => 'Surat Keterangan Aktif',
                'approval_flow' => ['dosen_pa', 'kaprodi'],
                'requires_number_generation' => false
            ],
            // Contoh: Surat Keterangan Lulus - perlu semua approval
            [
                'nama' => 'Surat Keterangan Lulus',
                'approval_flow' => ['dosen_pa', 'kaprodi', 'wadek1', 'bak'],
                'requires_number_generation' => true
            ],
            // Contoh: Surat Pengantar PKL - hanya Kaprodi dan Wadek
            [
                'nama' => 'Surat Pengantar PKL',
                'approval_flow' => ['kaprodi', 'wadek1'],
                'requires_number_generation' => true
            ],
            // Contoh: Surat Keterangan Bebas Laboratorium - hanya TU
            [
                'nama' => 'Surat Keterangan Bebas Laboratorium',
                'approval_flow' => ['tu'],
                'requires_number_generation' => false
            ],
            // Contoh: Surat Keterangan Berkelakuan Baik - Kaprodi dan BAK
            [
                'nama' => 'Surat Keterangan Berkelakuan Baik',
                'approval_flow' => ['kaprodi', 'bak'],
                'requires_number_generation' => true
            ]
        ];

        foreach ($jenisSuratFlows as $flowData) {
            \App\Models\JenisSurat::updateOrCreate(
                ['nama' => $flowData['nama']],
                [
                    'approval_flow' => $flowData['approval_flow'],
                    'requires_number_generation' => $flowData['requires_number_generation'],
                    'deskripsi' => 'Deskripsi untuk ' . $flowData['nama']
                ]
            );
        }

        // Update existing jenis surat yang belum memiliki approval flow
        \App\Models\JenisSurat::whereNull('approval_flow')->update([
            'approval_flow' => ['dosen_pa', 'kaprodi', 'wadek1', 'tu'], // Default flow
            'requires_number_generation' => true
        ]);

        $this->command->info('Approval flows updated successfully!');
    }
}
