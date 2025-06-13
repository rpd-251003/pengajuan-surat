<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('mahasiswa');
    }

    public function index_admin()
    {
        return view('dashboard');
    }

    public function index_mahasiswa()
    {

        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        $latestPengajuan = null;

        if ($mahasiswa) {
            $latestPengajuan = PengajuanSurat::with([
                'jenisSurat',
                'dosenPA',
                'kaprodi',
                'wadek1',
                'staffTU'
            ])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('created_at', 'desc')
                ->first(); // Get only the latest one
        }

        return view('mahasiswa.dashboard.index', compact('latestPengajuan'));
    }
}
