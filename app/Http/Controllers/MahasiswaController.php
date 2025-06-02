<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;

class MahasiswaController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanSurat::where('user_id', auth()->id())->latest()->get();
        return view('mahasiswa.dashboard', compact('pengajuan'));
    }

    public function create()
    {
        return view('mahasiswa.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        PengajuanSurat::create([
            'user_id' => auth()->id(),
            'jenis_surat' => $request->jenis_surat,
            'keterangan' => $request->keterangan,
            'status' => 'Menunggu Dosen PA',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Surat berhasil diajukan');
    }
}
