<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = $request->email;
        $password = $request->password;

        // Coba login dengan email dulu
        $user = User::where('email', $loginField)->first();

        // Jika tidak ditemukan dengan email, coba dengan nomor_identifikasi (NIM)
        if (!$user) {
            $user = User::where('nomor_identifikasi', $loginField)->first();
        }

        // Verifikasi password
        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email/NIM atau password salah.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect berdasarkan role
        return match ($user->role) {
            'mahasiswa' => redirect('/mahasiswa/dashboard'),
            'dosen' => redirect('/dosen/dashboard'),
            'kaprodi' => redirect('/kaprodi/dashboard'),
            'wadek1' => redirect('/wadek1/dashboard'),
            'tu' => redirect('/tu/dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
