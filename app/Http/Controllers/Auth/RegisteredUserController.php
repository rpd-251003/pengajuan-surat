<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $fakultas = Fakultas::all(); // ambil data fakultas dari DB
        return view('auth.register', compact('fakultas'));
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function getProdiByFakultas($fakultas_id)
    {
        $prodi = Prodi::where('fakultas_id', $fakultas_id)->get();
        return response()->json($prodi);
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ni' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'prodi_id' => ['required', 'exists:prodis,id'],
            'angkatan' => ['required', 'digits:4', 'integer', 'min:2000', 'max:' . date('Y')],
        ]);

        // Simpan user
        $user = User::create([
            'name' => $request->name,
            'role' => 'mahasiswa',
            'nomor_identifikasi' => $request->ni,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Simpan mahasiswa
        DB::table('mahasiswas')->insert([
            'user_id' => $user->id,
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
            'angkatan' => $request->angkatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
