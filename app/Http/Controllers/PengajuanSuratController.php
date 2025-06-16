<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function create()
    {

        $jenisSurats = JenisSurat::all();

        return view('pengajuan_surat.create', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        // Simpan data pengajuan surat, contoh pakai user login atau mahasiswa_id statis (ubah sesuai kebutuhan)
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Cari Kaprodi berdasarkan tahun_angkatan dan prodi_id
        $kaprodi = DB::table('kaprodi_tahunans')
            ->where('tahun_angkatan', $mahasiswa->angkatan)
            ->where('prodi_id', $mahasiswa->prodi_id)
            ->first();

        // Cari Dosen PA berdasarkan tahun_angkatan dan prodi_id
        $dosenPa = DB::table('dosen_pa_tahunans')
            ->where('tahun_angkatan', $mahasiswa->angkatan)
            ->where('prodi_id', $mahasiswa->prodi_id)
            ->first();

        // Validasi jika data kaprodi atau dosen PA tidak ditemukan
        if (!$kaprodi) {
            return redirect()->back()->with('error', 'Data Kaprodi untuk angkatan dan prodi Anda tidak ditemukan.');
        }

        if (!$dosenPa) {
            return redirect()->back()->with('error', 'Data Dosen PA untuk angkatan dan prodi Anda tidak ditemukan.');
        }

        $pengajuan = PengajuanSurat::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jenis_surat_id' => $request->jenis_surat,
            'keterangan' => $request->keterangan,
            'tahun_angkatan' => $mahasiswa->angkatan,
            'prodi_id' => $mahasiswa->prodi_id,
            'fakultas_id' => $mahasiswa->fakultas_id,
            'approved_by_kaprodi' => $kaprodi->user_id,
            'approved_by_dosen_pa' => $dosenPa->user_id,
        ]);

        return redirect()->route('pengajuan_surat.history')->with('success', 'Pengajuan surat berhasil dikirim.');
    }

    public function getDeskripsi(Request $request)
    {
        $id = $request->jenis_surat;

        $data = JenisSurat::find($id);

        if (!$data) {
            return response()->json(['deskripsi' => 'Deskripsi tidak ditemukan.']);
        }

        return response()->json(['deskripsi' => $data->deskripsi]);
    }

    // Method untuk menampilkan history pengajuan surat mahasiswa
    public function history()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengajuanSurats = PengajuanSurat::with([
            'jenisSurat',
            'dosenPA',
            'kaprodi',
            'wadek1',
            'staffTU'
        ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pengajuan_surat.history', compact('pengajuanSurats'));
    }

    public function index(Request $request)
    {
        $query = PengajuanSurat::with([
            'mahasiswa.user',
            'jenisSurat',
            'dosenPA',
            'kaprodi',
            'wadek1',
            'staffTU',
            'fileApproval' // <-- tambah ini
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('mahasiswa.user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('jenisSurat', function ($q) use ($searchTerm) {
                        $q->where('nama', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by jenis surat
        if ($request->filled('jenis_surat')) {
            $query->whereHas('jenisSurat', function ($q) use ($request) {
                $q->where('nama', $request->jenis_surat);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        $pengajuanSurats = $query->orderBy('created_at', 'desc')->paginate(5);

        // Get unique jenis surat for filter dropdown
        $jenisSuratOptions = PengajuanSurat::with('jenisSurat')
            ->get()
            ->pluck('jenisSurat.nama')
            ->unique()
            ->filter()
            ->values();

        return view('admin.pengajuan_surat.index', compact('pengajuanSurats', 'jenisSuratOptions'));
    }

    public function index_dosen()
    {

        $login_id = Auth::user()->id;
        $pengajuanSurats = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
            ->where(function ($query) use ($login_id) {
                $query->where('approved_by_dosen_pa', $login_id)
                    ->orWhere('approved_by_kaprodi', $login_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.pengajuan_surat.role.index', compact('pengajuanSurats'));
    }


    // ================= Dosen PA =================

    private function updateStatusAfterApproval(PengajuanSurat $pengajuan, $currentApprover = null)
    {
        $requiredApprovals = [
            'approved_at_dosen_pa',
            'approved_at_kaprodi',
            'approved_at_wadek1',
            'approved_at_staff_tu',
        ];

        $otherApprovals = $currentApprover
            ? array_filter($requiredApprovals, fn($field) => $field !== $currentApprover)
            : $requiredApprovals;

        $isAllApproved = true;
        foreach ($otherApprovals as $field) {
            if (empty($pengajuan->$field)) {
                $isAllApproved = false;
                break;
            }
        }

        if ($isAllApproved) {
            $pengajuan->status = 'disetujui';
        } elseif ($pengajuan->status === 'diajukan') {
            $pengajuan->status = 'diproses';
        }

        $pengajuan->save();
    }


    public function approveDouble($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Cek apakah sebelumnya sudah disetujui
        if ($pengajuan->approved_at_dosen_pa && $pengajuan->approved_at_kaprodi) {
            return redirect()->back()->with('error', 'Sudah disetujui oleh Dosen PA dan Kaprodi sebelumnya.');
        }

        $pengajuan->approved_by_dosen_pa = Auth::id();
        $pengajuan->approved_at_dosen_pa = now();

        $pengajuan->approved_by_kaprodi = Auth::id();
        $pengajuan->approved_at_kaprodi = now();

        $pengajuan->save();

        // Evaluasi status setelah approve ganda
        $this->updateStatusAfterApproval($pengajuan, null); // null karena 2 sekaligus

        return redirect()->back()->with('success', 'Disetujui oleh Dosen PA & Kaprodi.');
    }



    public function approveDosenPA($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_dosen_pa) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_dosen_pa = Auth::id();
        $pengajuan->approved_at_dosen_pa = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_dosen_pa');

        return redirect()->back()->with('success', 'Disetujui oleh Dosen PA.');
    }

    public function approveKaprodi($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_kaprodi) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_kaprodi = Auth::id();
        $pengajuan->approved_at_kaprodi = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_kaprodi');

        return redirect()->back()->with('success', 'Disetujui oleh Kaprodi.');
    }

    public function approveWadek1($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_wadek1) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_wadek1 = Auth::id();
        $pengajuan->approved_at_wadek1 = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_wadek1');

        return redirect()->back()->with('success', 'Disetujui oleh Wadek 1.');
    }

    public function approveStaffTU($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_staff_tu) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_staff_tu = Auth::id();
        $pengajuan->approved_at_staff_tu = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_staff_tu');

        return redirect()->back()->with('success', 'Disetujui oleh Staff TU.');
    }


    public function rejectDosenPA(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_dosen_pa = null;
        $pengajuan->approved_at_dosen_pa = null;
        // update keterangan dengan alasan reject
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Dosen PA: ' . $request->alasan_reject;
        $pengajuan->save();

        $pengajuan->status = 'diproses';
        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Dosen PA.');
    }

    public function rejectDouble(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_at_kaprodi = null;
        $pengajuan->approved_at_dosen_pa = null;
        // update keterangan dengan alasan reject
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Dosen PA / Kaprodi: ' . $request->alasan_reject;
        $pengajuan->save();

        $pengajuan->status = 'diproses';
        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Dosen PA.');
    }



    // ================= Kaprodi =================



    public function rejectKaprodi(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_kaprodi = null;
        $pengajuan->approved_at_kaprodi = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Kaprodi: ' . $request->alasan_reject;
        $pengajuan->save();

        $pengajuan->status = 'diproses';
        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kaprodi.');
    }

    // ================= Wadek1 =================



    public function rejectWadek1(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_wadek1 = null;
        $pengajuan->approved_at_wadek1 = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Wadek 1: ' . $request->alasan_reject;
        $pengajuan->save();

        $pengajuan->status = 'diproses';
        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Wadek 1.');
    }

    // ================= Staff TU =================



    public function rejectStaffTU(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_staff_tu = null;
        $pengajuan->approved_at_staff_tu = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Staff TU: ' . $request->alasan_reject;
        $pengajuan->save();

        $pengajuan->status = 'diproses';
        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Staff TU.');
    }
}
