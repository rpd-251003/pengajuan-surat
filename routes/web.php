<?php

use App\Models\Fakultas;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TuController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WadekController;
use App\Http\Controllers\DosenPaController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\FileApprovalController;
use App\Http\Controllers\DosenPaTahunanController;
use App\Http\Controllers\KaprodiTahunanController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\JenisSuratFieldController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SuratTemplateController;
use App\Http\Controllers\PDFGeneratorController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [HomeController::class, 'index_admin'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/prodi-by-fakultas/{fakultas_id}', [RegisteredUserController::class, 'getProdiByFakultas']);

// Dosen PA
Route::middleware(['auth', RoleMiddleware::class . ':dosen'])->group(function () {
    Route::get('/dosen/dashboard', [HomeController::class, 'index_admin'])->name('dosenpa.dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Template CRUD
    Route::resource('template', SuratTemplateController::class);
    Route::get('template-data', [SuratTemplateController::class, 'getData'])->name('template.getData');
    Route::post('template/toggle-status/{id}', [SuratTemplateController::class, 'toggleStatus'])->name('template.toggleStatus');
    Route::get('template/preview/{id}', [SuratTemplateController::class, 'preview'])->name('template.preview');
    Route::get('template/by-jenis/{jenisId}', [SuratTemplateController::class, 'getTemplatesByJenis'])->name('template.byJenis');

    // PDF Generation Routes
    Route::post('generate-pdf/{pengajuanId}', [PDFGeneratorController::class, 'generateSuratPDF'])->name('generate.pdf');
    Route::get('preview-surat/{pengajuanId}', [PDFGeneratorController::class, 'previewSurat'])->name('preview.surat');
    Route::get('download-surat/{pengajuanId}', [PDFGeneratorController::class, 'downloadSurat'])->name('download.surat');
    Route::post('bulk-generate-pdf', [PDFGeneratorController::class, 'bulkGenerate'])->name('bulk.generate.pdf');

});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('template/{id}', [SuratTemplateController::class, 'show']);
    Route::delete('template/{id}', [SuratTemplateController::class, 'destroy']);
    Route::post('template/toggle-status/{id}', [SuratTemplateController::class, 'toggleStatus']);
});
// Kaprodi
Route::middleware(['auth', RoleMiddleware::class . ':kaprodi'])->group(function () {
    Route::get('/kaprodi/dashboard', [HomeController::class, 'index_admin'])->name('kaprodi.dashboard');
    Route::post('/kaprodi/approve/{id}', [KaprodiController::class, 'approve']);
    Route::post('/kaprodi/reject/{id}', [KaprodiController::class, 'reject']);
});

// Wadek
Route::middleware(['auth', RoleMiddleware::class . ':wadek1'])->group(function () {
    Route::get('/wadek1/dashboard', [HomeController::class, 'index_admin'])->name('wadek.dashboard');
    Route::post('/wadek1/approve/{id}', [WadekController::class, 'approve']);
    Route::post('/wadek1/reject/{id}', [WadekController::class, 'reject']);
});

// Wadek
Route::middleware(['auth', RoleMiddleware::class . ':mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [HomeController::class, 'index_mahasiswa'])->name('mahasiswa.dashboard');
    Route::get('/pengajuan-surat/history', [PengajuanSuratController::class, 'history'])->name('pengajuan_surat.history');
});


// TU
Route::middleware(['auth', RoleMiddleware::class . ':tu'])->group(function () {
    Route::get('/tu/dashboard', [HomeController::class, 'index_admin'])->name('tu.dashboard');
    Route::get('/tu/laporan', [TuController::class, 'laporanBulanan'])->name('tu.laporan');

    Route::resource('users', UsersController::class);

    Route::resource('file-approvals', FileApprovalController::class)->except(['create', 'edit', 'show']);
    Route::get('/file-approvals/data', [FileApprovalController::class, 'data'])->name('file-approvals.data');

    Route::prefix('jenis-surat-fields')->group(function () {
        Route::get('/{jenisSuratId}', [JenisSuratFieldController::class, 'index'])->name('jenis-surat-fields.index');
        Route::post('/', [JenisSuratFieldController::class, 'store'])->name('jenis-surat-fields.store');
        Route::get('/{id}/show', [JenisSuratFieldController::class, 'show']);
        Route::delete('/{id}', [JenisSuratFieldController::class, 'destroy']);
    });

    // API route for getting fields by jenis surat
    Route::get('/api/jenis-surat/{jenisSuratId}/fields', [JenisSuratFieldController::class, 'getFields'])->name('api.jenis-surat.fields');


    Route::resource('kaprodi-tahunan', KaprodiTahunanController::class);
    Route::resource('dosen-pa-tahunan', DosenPaTahunanController::class);

    Route::prefix('jenis-surat')->group(function () {
        Route::get('/', [JenisSuratController::class, 'index'])->name('jenis-surat.index');
        Route::get('/data', [JenisSuratController::class, 'getData'])->name('jenis-surat.data');
        Route::post('/', [JenisSuratController::class, 'store'])->name('jenis-surat.store');
        Route::get('/{id}', [JenisSuratController::class, 'show']);
        Route::delete('/{id}', [JenisSuratController::class, 'destroy']);
    });

    Route::get('/api/jenis-surat/{jenisSuratId}/fields', [JenisSuratFieldController::class, 'getFields'])->name('api.jenis-surat.fields');


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

    Route::get('/pengajuan-surat/create', [PengajuanSuratController::class, 'create'])->name('pengajuan_surat.create');
    Route::post('/pengajuan-surat/store', [PengajuanSuratController::class, 'store'])->name('pengajuan_surat.store');
    Route::get('/pengajuan-surat/deskripsi', [PengajuanSuratController::class, 'getDeskripsi'])->name('pengajuan_surat.deskripsi');
    Route::get('/pengajuan-surat/form-fields', [PengajuanSuratController::class, 'getFormFields'])->name('pengajuan_surat.form_fields');


    Route::get('/dashboard/chart-data', [HomeController::class, 'chartData']);
    Route::get('/dashboard/pending/{type}', [HomeController::class, 'pendingApprovalDetails']);
    Route::get('/dashboard/stats', [HomeController::class, 'getStats']);
});

Route::prefix('data/pengajuan')->middleware(['auth'])->group(function () {
    Route::get('/', [PengajuanSuratController::class, 'index'])->name('admin.pengajuan.index');
    Route::get('/dosen', [PengajuanSuratController::class, 'index_dosen'])->name('admin.pengajuan.dosen');

    // Approve by each level
    Route::patch('/{id}/approve_dosen_pa', [PengajuanSuratController::class, 'approveDosenPA'])->name('admin.pengajuan.approve_dosen_pa');
    Route::patch('/{id}/approve_kaprodi', [PengajuanSuratController::class, 'approveKaprodi'])->name('admin.pengajuan.approve_kaprodi');
    Route::patch('/{id}/approve_wadek1', [PengajuanSuratController::class, 'approveWadek1'])->name('admin.pengajuan.approve_wadek1');
    Route::patch('/{id}/approve_staff_tu', [PengajuanSuratController::class, 'approveStaffTU'])->name('admin.pengajuan.approve_staff_tu');
    Route::patch('/{id}/approve_double', [PengajuanSuratController::class, 'approveDouble'])->name('admin.pengajuan.approve_double');

    // Reject by each level (dengan alasan)
    Route::patch('/{id}/reject_dosen_pa', [PengajuanSuratController::class, 'rejectDosenPA'])->name('admin.pengajuan.reject_dosen_pa');
    Route::patch('/{id}/reject_kaprodi', [PengajuanSuratController::class, 'rejectKaprodi'])->name('admin.pengajuan.reject_kaprodi');
    Route::patch('/{id}/reject_wadek1', [PengajuanSuratController::class, 'rejectWadek1'])->name('admin.pengajuan.reject_wadek1');
    Route::patch('/{id}/reject_staff_tu', [PengajuanSuratController::class, 'rejectStaffTU'])->name('admin.pengajuan.reject_staff_tu');
    Route::patch('/{id}/reject_double', [PengajuanSuratController::class, 'rejectDouble'])->name('admin.pengajuan.reject_double');
});
Route::middleware('auth')->group(function () {
    // File operations
    Route::get('/pengajuan/{pengajuanId}/file/{fieldName}/download', [PengajuanSuratController::class, 'downloadFile'])
        ->name('pengajuan.file.download');

    Route::get('/pengajuan/{pengajuanId}/file/{fieldName}/view', [PengajuanSuratController::class, 'viewFile'])
        ->name('pengajuan.file.view');
});

require __DIR__ . '/auth.php';
