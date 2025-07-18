<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $nim = $row['nim'];
            $nama = $row['nama'];
            $password = $row['password'];
            
            // Check if user already exists
            $existingUser = User::where('nomor_identifikasi', $nim)->first();
            if ($existingUser) {
                continue; // Skip if user already exists
            }
            
            // Create user
            $user = User::create([
                'name' => $nama,
                'email' => $nim . '@student.example.com', // Generate email from NIM
                'nomor_identifikasi' => $nim,
                'password' => Hash::make($password),
                'role' => 'mahasiswa',
                'email_verified_at' => now()
            ]);
            
            // Create mahasiswa data
            $this->createMahasiswaData($user, $nim);
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nim' => 'required|string|unique:users,nomor_identifikasi',
            '*.nama' => 'required|string|max:255',
            '*.password' => 'required|string|min:6'
        ];
    }

    /**
     * Create mahasiswa data automatically based on NIM
     */
    private function createMahasiswaData($user, $nim)
    {
        // Extract tahun angkatan dan kode prodi dari NIM
        if (strlen($nim) >= 6) {
            $tahunAngkatan = substr($nim, 0, 4); // 4 digit pertama
            $kodeProdi = substr($nim, 4, 2); // 2 digit berikutnya
            
            // Mapping kode prodi
            $prodiMapping = [
                '21' => 'Teknik Elektro',
                '22' => 'Teknik Industri', 
                '23' => 'Teknik Teknologi Informasi',
                '24' => 'Sistem Informasi',
                '25' => 'Teknik Mesin'
            ];
            
            if (isset($prodiMapping[$kodeProdi])) {
                $namaProdi = $prodiMapping[$kodeProdi];
                
                // Cari atau buat prodi
                $prodi = Prodi::where('nama', $namaProdi)->first();
                if (!$prodi) {
                    // Jika prodi belum ada, buat dengan fakultas default
                    $fakultas = Fakultas::first();
                    if ($fakultas) {
                        $prodi = Prodi::create([
                            'nama' => $namaProdi,
                            'kode' => $kodeProdi,
                            'fakultas_id' => $fakultas->id
                        ]);
                    }
                }
                
                if ($prodi) {
                    // Buat data mahasiswa
                    Mahasiswa::create([
                        'user_id' => $user->id,
                        'nim' => $nim,
                        'angkatan' => $tahunAngkatan,
                        'prodi_id' => $prodi->id,
                        'fakultas_id' => $prodi->fakultas_id
                    ]);
                }
            }
        }
    }
}