<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;

class DosenPaController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanSurat::whereHas('user', function ($q) {
            $q->where('dosen_pa_id', auth()->id());
        })->where('status', 'Menunggu Dosen PA')->get();
        return view('dosen_pa.dashboard', compact('pengajuan'));
    }

    public function approve($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->update(['status' => 'Menunggu Kaprodi']);

        return back()->with('success', 'Pengajuan disetujui dan diteruskan ke Kaprodi.');
    }
}
