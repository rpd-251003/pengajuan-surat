<?php

use App\Http\Controllers\DosenPaController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TuController;
use App\Http\Controllers\WadekController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Dosen PA
Route::middleware(['auth', RoleMiddleware::class.':dosen_pa'])->group(function () {
    Route::get('/dosen-pa/dashboard', [DosenPaController::class, 'index'])->name('dosenpa.dashboard');
    Route::post('/dosen-pa/approve/{id}', [DosenPaController::class, 'approve'])->name('dosenpa.approve');
});

// Kaprodi
Route::middleware(['auth', 'role:kaprodi'])->group(function () {
    Route::get('/kaprodi/dashboard', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');
    Route::post('/kaprodi/approve/{id}', [KaprodiController::class, 'approve']);
    Route::post('/kaprodi/reject/{id}', [KaprodiController::class, 'reject']);
});

// Wadek
Route::middleware(['auth', 'role:wadek1'])->group(function () {
    Route::get('/wadek1/dashboard', [WadekController::class, 'index'])->name('wadek.dashboard');
    Route::post('/wadek1/approve/{id}', [WadekController::class, 'approve']);
    Route::post('/wadek1/reject/{id}', [WadekController::class, 'reject']);
});

// TU
Route::middleware(['auth', 'role:tu'])->group(function () {
    Route::get('/tu/dashboard', [TuController::class, 'index'])->name('tu.dashboard');
    Route::get('/tu/laporan', [TuController::class, 'laporanBulanan'])->name('tu.laporan');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
