<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Mahasiswa;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
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

        $pengajuan = PengajuanSurat::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jenis_surat_id' => $request->jenis_surat,
            'keterangan' => $request->keterangan,
            'tahun_angkatan' => $mahasiswa->angkatan,
            'prodi_id' => $mahasiswa->prodi_id,
            'fakultas_id' => $mahasiswa->fakultas_id,
        ]);

        return redirect()->route('pengajuan_surat.create')->with('success', 'Pengajuan surat berhasil dikirim.');
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

    public function index()
    {
        $pengajuans = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])->paginate(15);
        return view('admin.pengajuan_surat.index', compact('pengajuans'));
    }

    // ================= Dosen PA =================

    public function approveDosenPA($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_dosen_pa = Auth::id();
        $pengajuan->approved_at_dosen_pa = now();
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Dosen PA.');
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
        $pengajuan->keterangan = 'Ditolak oleh Dosen PA: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Dosen PA.');
    }


    // ================= Kaprodi =================

    public function approveKaprodi($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_kaprodi = Auth::id();
        $pengajuan->approved_at_kaprodi = now();
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Kaprodi.');
    }

    public function rejectKaprodi(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_kaprodi = null;
        $pengajuan->approved_at_kaprodi = null;
        $pengajuan->keterangan = 'Ditolak oleh Kaprodi: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kaprodi.');
    }

    // ================= Wadek1 =================

    public function approveWadek1($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_wadek1 = Auth::id();
        $pengajuan->approved_at_wadek1 = now();
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Wadek 1.');
    }

    public function rejectWadek1(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_wadek1 = null;
        $pengajuan->approved_at_wadek1 = null;
        $pengajuan->keterangan = 'Ditolak oleh Wadek 1: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Wadek 1.');
    }

    // ================= Staff TU =================

    public function approveStaffTU($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_staff_tu = Auth::id();
        $pengajuan->approved_at_staff_tu = now();
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Staff TU.');
    }

    public function rejectStaffTU(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_staff_tu = null;
        $pengajuan->approved_at_staff_tu = null;
        $pengajuan->keterangan = 'Ditolak oleh Staff TU: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Staff TU.');
    }
}
