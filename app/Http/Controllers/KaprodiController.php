<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class KaprodiController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}
