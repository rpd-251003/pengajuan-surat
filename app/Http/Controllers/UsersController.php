<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use App\Imports\MahasiswaImport;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'role', 'nomor_identifikasi', 'created_at']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info me-1" onclick="showUser(' . $row->id . ')"><i class="ti ti-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning me-1" onclick="editUser(' . $row->id . ')"><i class="ti ti-edit"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(' . $row->id . ')"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('role_badge', function ($row) {
                    $badges = [
                        'admin' => 'bg-primary',
                        'mahasiswa' => 'bg-success',
                        'dosen' => 'bg-info',
                        'kaprodi' => 'bg-warning',
                        'wadek1' => 'bg-secondary',
                        'tu' => 'bg-dark',
                        'bak' => 'bg-purple'
                    ];
                    $class = $badges[$row->role] ?? 'bg-secondary';
                    return '<span class="badge ' . $class . '">' . $row->getRoleDisplayName() . '</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action', 'role_badge'])
                ->make(true);
        }

        return view('tu.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'html' => view('tu.users.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa,dosen,kaprodi,wadek1,tu,bak',
            'nomor_identifikasi' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nomor_identifikasi' => $request->nomor_identifikasi,
                'email_verified_at' => now()
            ]);

            // Auto-create mahasiswa data if role is mahasiswa
            if ($request->role === 'mahasiswa' && $request->nomor_identifikasi) {
                $this->createMahasiswaData($user, $request->nomor_identifikasi);
            }

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'html' => view('tu.users.show', compact('user'))->render()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'html' => view('tu.users.edit', compact('user'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa,dosen,kaprodi,wadek1,tu,bak',
            'nomor_identifikasi' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'nomor_identifikasi' => $request->nomor_identifikasi,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting current user
            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri!'
                ]);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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

    /**
     * Import bulk mahasiswa from Excel
     */
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diimpor!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export mahasiswa data to Excel
     */
    public function exportMahasiswa()
    {
        return Excel::download(new MahasiswaExport, 'data_mahasiswa.xlsx');
    }

    /**
     * Download sample Excel file for import
     */
    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_import_mahasiswa.csv"',
        ];

        $sampleData = [
            ['NIM', 'Nama', 'Password'],
            ['2021240001', 'Ahmad Fauzi', 'password123'],
            ['2021240002', 'Siti Nurhaliza', 'password123'],
            ['2021220001', 'Budi Santoso', 'password123'],
            ['2021210001', 'Andi Wijaya', 'password123'],
            ['2021230001', 'Rina Susanti', 'password123'],
            ['2021250001', 'Dedi Kurniawan', 'password123']
        ];

        return response()->stream(function () use ($sampleData) {
            $handle = fopen('php://output', 'w');
            foreach ($sampleData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
