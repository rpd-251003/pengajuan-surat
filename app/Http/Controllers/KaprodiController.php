<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class KaprodiController extends Controller
{
    public function index()
    {
        $kaprodi = Auth::user(); // user yang sedang login
        $pengajuan = PengajuanSurat::where('status', 'Menunggu Kaprodi')
            ->whereHas('user', function ($query) use ($kaprodi) {
                $query->where('prodi_id', $kaprodi->prodi_id);
            })
            ->get();

        return view('kaprodi.dashboard', compact('pengajuan'));
    }

    public function approve($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Validasi apakah surat berasal dari prodi yang sama
        if ($pengajuan->user->prodi_id !== Auth::user()->prodi_id) {
            abort(403, 'Tidak berhak menyetujui surat ini.');
        }

        $pengajuan->update(['status' => 'Menunggu Wadek 1']);

        return back()->with('success', 'Pengajuan diteruskan ke Wadek 1.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->user->prodi_id !== Auth::user()->prodi_id) {
            abort(403, 'Tidak berhak menolak surat ini.');
        }

        $pengajuan->update(['status' => 'Ditolak oleh Kaprodi']);

        return back()->with('error', 'Pengajuan ditolak.');
    }
}
