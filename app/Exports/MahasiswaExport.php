<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('mahasiswa.prodi', 'mahasiswa.fakultas')
            ->where('role', 'mahasiswa')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Email',
            'Angkatan',
            'Prodi',
            'Fakultas',
            'Tanggal Dibuat'
        ];
    }

    /**
     * @var User $user
     */
    public function map($user): array
    {
        return [
            $user->nomor_identifikasi,
            $user->name,
            $user->email,
            $user->mahasiswa->angkatan ?? '-',
            $user->mahasiswa->prodi->nama ?? '-',
            $user->mahasiswa->fakultas->nama ?? '-',
            $user->created_at->format('d/m/Y')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}