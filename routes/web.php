<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DosenPaController;
use App\Http\Controllers\DosenPaTahunanController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KaprodiTahunanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TuController;
use App\Http\Controllers\WadekController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Fakultas;
use Illuminate\Support\Facades\Route;









Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/prodi-by-fakultas/{fakultas_id}', [RegisteredUserController::class, 'getProdiByFakultas']);

// Dosen PA
Route::middleware(['auth', RoleMiddleware::class . ':dosen_pa'])->group(function () {
    Route::get('/dosen-pa/dashboard', [DosenPaController::class, 'index'])->name('dosenpa.dashboard');
    Route::post('/dosen-pa/approve/{id}', [DosenPaController::class, 'approve'])->name('dosenpa.approve');
});

// Kaprodi
Route::middleware(['auth', RoleMiddleware::class . ':kaprodi'])->group(function () {
    Route::get('/kaprodi/dashboard', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');
    Route::post('/kaprodi/approve/{id}', [KaprodiController::class, 'approve']);
    Route::post('/kaprodi/reject/{id}', [KaprodiController::class, 'reject']);
});

// Wadek
Route::middleware(['auth', RoleMiddleware::class . ':wadek1'])->group(function () {
    Route::get('/wadek1/dashboard', [WadekController::class, 'index'])->name('wadek.dashboard');
    Route::post('/wadek1/approve/{id}', [WadekController::class, 'approve']);
    Route::post('/wadek1/reject/{id}', [WadekController::class, 'reject']);
});

// TU
Route::middleware(['auth', RoleMiddleware::class . ':tu'])->group(function () {
    Route::get('/tu/dashboard', [TuController::class, 'index'])->name('tu.dashboard');
    Route::get('/tu/laporan', [TuController::class, 'laporanBulanan'])->name('tu.laporan');


    Route::resource('kaprodi-tahunan', KaprodiTahunanController::class);
    Route::resource('dosen-pa-tahunan', DosenPaTahunanController::class);


    // Fakultas
    Route::get('fakultas-data', [FakultasController::class, 'getData'])->name('fakultas.data');
    Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas.index');
    Route::get('/fakultas/create', [FakultasController::class, 'create'])->name('fakultas.create');
    Route::post('/fakultas', [FakultasController::class, 'store'])->name('fakultas.store');
    Route::get('/fakultas/{fakultas}', [FakultasController::class, 'show'])->name('fakultas.show');
    Route::get('/fakultas/{fakultas}/edit', [FakultasController::class, 'edit'])->name('fakultas.edit');
    Route::put('/fakultas/{fakultas}', [FakultasController::class, 'update'])->name('fakultas.update');
    Route::delete('/fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');

    // Prodi

    Route::get('/list-fakultas', function () {
        return \App\Models\Fakultas::select('id', 'nama')->get();
    });
    Route::get('/prodi/data', [ProdiController::class, 'data'])->name('prodi.data');
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::get('/prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('/prodi', [ProdiController::class, 'store'])->name('prodi.store');
    Route::get('/prodi/{prodi}', [ProdiController::class, 'show'])->name('prodi.show');
    Route::get('/prodi/{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
    Route::put('/prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');

    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/data', [MahasiswaController::class, 'data'])->name('mahasiswa.data');
    Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::post('/mahasiswa/{id}/update', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
