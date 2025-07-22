<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function create()
    {
        $jenisSurats = JenisSurat::with('fields')->get();

        // Get current user data
        $currentUser = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $currentUser->id)->first();

        $userData = [
            'nama' => $currentUser->name,
            'nim' => $currentUser->nomor_identifikasi,
            'fakultas' => $mahasiswa->fakultas->nama ?? '',
            'prodi' => $mahasiswa->prodi->nama ?? '',
            'angkatan' => $mahasiswa->angkatan ?? ''
        ];

        return view('pengajuan_surat.create', compact('jenisSurats', 'userData'));
    }

    public function store(Request $request)
    {
        try {
            // Log untuk debug
            \Log::info('=== START PENGAJUAN SURAT ===');
            \Log::info('Request data', $request->all());

            $request->validate([
                'jenis_surat' => 'required|string',
                'keterangan' => 'nullable|string',
            ]);

            \Log::info('Validation passed');

            // Validasi dynamic fields berdasarkan jenis surat
            $this->validateDynamicFields($request);

            \Log::info('Dynamic fields validation passed');

            $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

            \Log::info('Mahasiswa found', $mahasiswa ? $mahasiswa->toArray() : ['mahasiswa' => 'NULL']);

            if (!$mahasiswa) {
                \Log::error('Mahasiswa not found', ['user_id' => Auth::id()]);
                return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            // Cari Kaprodi berdasarkan tahun_angkatan dan prodi_id
            $kaprodi = DB::table('kaprodi_tahunans')
                ->where('tahun_angkatan', $mahasiswa->angkatan)
                ->where('prodi_id', $mahasiswa->prodi_id)
                ->first();

            \Log::info('Kaprodi search result', $kaprodi ? (array)$kaprodi : ['kaprodi' => 'NULL']);

            // Cari Dosen PA berdasarkan tahun_angkatan dan prodi_id
            $dosenPa = DB::table('dosen_pa_tahunans')
                ->where('tahun_angkatan', $mahasiswa->angkatan)
                ->where('prodi_id', $mahasiswa->prodi_id)
                ->first();

            \Log::info('Dosen PA search result', $dosenPa ? (array)$dosenPa : ['dosen_pa' => 'NULL']);

            // Validasi jika data kaprodi atau dosen PA tidak ditemukan
            if (!$kaprodi) {
                \Log::error('Kaprodi not found', [
                    'angkatan' => $mahasiswa->angkatan,
                    'prodi_id' => $mahasiswa->prodi_id
                ]);
                return redirect()->back()->with('error', 'Data Kaprodi untuk angkatan dan prodi Anda tidak ditemukan.');
            }

            if (!$dosenPa) {
                \Log::error('Dosen PA not found', [
                    'angkatan' => $mahasiswa->angkatan,
                    'prodi_id' => $mahasiswa->prodi_id
                ]);
                return redirect()->back()->with('error', 'Data Dosen PA untuk angkatan dan prodi Anda tidak ditemukan.');
            }

            \Log::info('Starting to create pengajuan...');

            // Create pengajuan surat
            $pengajuan = PengajuanSurat::create([
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $request->jenis_surat,
                'keterangan' => $request->keterangan,
                'tahun_angkatan' => $mahasiswa->angkatan,
                'prodi_id' => $mahasiswa->prodi_id,
                'fakultas_id' => $mahasiswa->fakultas_id,
                'approved_by_kaprodi' => $kaprodi->user_id,
                'approved_by_dosen_pa' => $dosenPa->user_id,
            ]);

            \Log::info('Pengajuan created successfully', $pengajuan->toArray());

            // Initialize dynamic approval flow
            $pengajuan->initializeApprovalFlow();

            // Simpan detail pengajuan dengan auto fields
            $this->storeDetailsWithAutoFields($request, $pengajuan->id);

            \Log::info('Details stored successfully');
            \Log::info('=== END PENGAJUAN SURAT SUCCESS ===');

            return redirect()->route('pengajuan_surat.history')->with('success', 'Pengajuan surat berhasil dikirim.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in store', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in store pengajuan surat', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store pengajuan details with auto fields
     */
    /**
     * Store pengajuan details with auto fields and file support
     */
    private function storeDetailsWithAutoFields(Request $request, $pengajuanId)
    {
        try {
            $currentUser = Auth::user();
            $mahasiswa = Mahasiswa::where('user_id', $currentUser->id)->first();

            // Auto fields
            $autoDetails = [
                'nama' => $currentUser->name,
                'nim' => $currentUser->nomor_identifikasi,
                'fakultas' => $mahasiswa->fakultas->nama ?? '',
                'prodi' => $mahasiswa->prodi->nama ?? '',
                'angkatan' => $mahasiswa->angkatan ?? ''
            ];

            // User input
            $excludedFields = ['_token', 'jenis_surat', 'keterangan'];
            $userDetails = $request->except($excludedFields);

            // Merge all details
            $allDetails = array_merge($autoDetails, $userDetails);

            // Get uploaded files
            $uploadedFiles = $request->allFiles();

            // Store details with file support
            \App\Models\PengajuanDetail::storeDetails($pengajuanId, $allDetails, $uploadedFiles);
        } catch (\Exception $e) {
            \Log::error('Error storing details', [
                'message' => $e->getMessage(),
                'pengajuan_id' => $pengajuanId
            ]);
            throw $e;
        }
    }

    /**
     * Download file
     */
    public function downloadFile($pengajuanId, $fieldName)
    {
        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        // Check permission
        $user = Auth::user();
        if ($pengajuan->mahasiswa->user_id !== $user->id && !in_array($user->role, ['tu', 'wadek1', 'kaprodi', 'dosen'])) {
            abort(403);
        }

        $detail = PengajuanDetail::where('pengajuan_surat_id', $pengajuanId)
            ->where('field_name', $fieldName)
            ->where('field_type', 'file')
            ->firstOrFail();

        $fileInfo = json_decode($detail->field_value, true);
        $filePath = storage_path('app/public/' . $fileInfo['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $fileInfo['original_name']);
    }

    /**
     * View file
     */
    public function viewFile($pengajuanId, $fieldName)
    {
        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        // Check permission
        $user = Auth::user();
        if ($pengajuan->mahasiswa->user_id !== $user->id && !in_array($user->role, ['tu', 'wadek1', 'kaprodi', 'dosen'])) {
            abort(403);
        }

        $detail = PengajuanDetail::where('pengajuan_surat_id', $pengajuanId)
            ->where('field_name', $fieldName)
            ->where('field_type', 'file')
            ->firstOrFail();

        $fileInfo = json_decode($detail->field_value, true);
        $filePath = storage_path('app/public/' . $fileInfo['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath);
    }

    /**
     * Validate dynamic fields based on jenis surat
     */
    private function validateDynamicFields(Request $request)
    {
        try {
            \Log::info('=== START VALIDATE DYNAMIC FIELDS ===');

            $jenisSurat = JenisSurat::with('fields')->findOrFail($request->jenis_surat);

            \Log::info('Jenis surat found', $jenisSurat->toArray());

            $rules = [];
            $messages = [];

            foreach ($jenisSurat->fields as $field) {
                \Log::info('Processing field', $field->toArray());

                // Skip auto fields (nama, nim) karena sudah otomatis
                if (in_array($field->field_name, ['nama', 'nim'])) {
                    continue;
                }

                if ($field->is_required) {
                    if ($field->field_type === 'checkbox') {
                        $rules[$field->field_name] = 'required|array|min:1';
                        $messages[$field->field_name . '.required'] = $field->field_label . ' harus dipilih minimal 1 item.';
                        $messages[$field->field_name . '.min'] = $field->field_label . ' harus dipilih minimal 1 item.';
                    } else {
                        $rules[$field->field_name] = 'required';
                        $messages[$field->field_name . '.required'] = $field->field_label . ' wajib diisi.';
                    }
                }

                // Add validation rules from field configuration
                if ($field->validation_rules && is_array($field->validation_rules)) {
                    $additionalRules = implode('|', $field->validation_rules);
                    if (isset($rules[$field->field_name])) {
                        $rules[$field->field_name] .= '|' . $additionalRules;
                    } else {
                        $rules[$field->field_name] = $additionalRules;
                    }
                }
            }

            \Log::info('Validation rules', $rules);
            \Log::info('Validation messages', $messages);

            if (!empty($rules)) {
                $request->validate($rules, $messages);
            }

            \Log::info('=== END VALIDATE DYNAMIC FIELDS SUCCESS ===');
        } catch (\Exception $e) {
            \Log::error('Error in validateDynamicFields', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }
    /**
     * Get dynamic form fields for specific jenis surat
     */
    public function getFormFields(Request $request)
    {
        $jenisSuratId = $request->jenis_surat;
        $jenisSurat = JenisSurat::with('fields')->findOrFail($jenisSuratId);

        // Filter out auto fields dari fields yang akan ditampilkan di form
        $dynamicFields = $jenisSurat->fields->filter(function ($field) {
            return !in_array($field->field_name, ['nama', 'nim', 'fakultas', 'prodi', 'angkatan']);
        });

        return response()->json([
            'fields' => $dynamicFields,
            'deskripsi' => $jenisSurat->deskripsi
        ]);
    }

    public function getDeskripsi(Request $request)
    {
        $id = $request->jenis_surat;
        $data = JenisSurat::with('fields')->find($id);

        if (!$data) {
            return response()->json(['deskripsi' => 'Deskripsi tidak ditemukan.']);
        }

        // Filter out auto fields
        $dynamicFields = $data->fields->filter(function ($field) {
            return !in_array($field->field_name, ['nama', 'nim', 'fakultas', 'prodi', 'angkatan']);
        });

        return response()->json([
            'deskripsi' => $data->deskripsi,
            'fields' => $dynamicFields
        ]);
    }

    // Method untuk menampilkan history pengajuan surat mahasiswa
    public function history()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengajuanSurats = PengajuanSurat::with([
            'jenisSurat.fields',
            'dosenPA',
            'kaprodi',
            'wadek1',
            'staffTU',
            'fileApproval',
            'details'
        ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pengajuan_surat.history', compact('pengajuanSurats'));
    }

    public function index(Request $request)
    {
        $query = PengajuanSurat::with([
            'mahasiswa.user',
            'jenisSurat',
            'dosenPA',
            'kaprodi',
            'wadek1',
            'staffTU',
            'fileApproval'
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('mahasiswa.user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('jenisSurat', function ($q) use ($searchTerm) {
                        $q->where('nama', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by jenis surat
        if ($request->filled('jenis_surat')) {
            $query->whereHas('jenisSurat', function ($q) use ($request) {
                $q->where('nama', $request->jenis_surat);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuanSurats = $query->orderBy('created_at', 'desc')->paginate(5);

        // Get unique jenis surat for filter dropdown
        $jenisSuratOptions = PengajuanSurat::with('jenisSurat')
            ->get()
            ->pluck('jenisSurat.nama')
            ->unique()
            ->filter()
            ->values();

        return view('admin.pengajuan_surat.index', compact('pengajuanSurats', 'jenisSuratOptions'));
    }

    public function index_dosen()
    {
        $user = Auth::user();
        $login_id = $user->id;

        $pengajuanSurats = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($pengajuan) use ($user, $login_id) {
                if (!$pengajuan->jenisSurat) {
                    return false;
                }

                // Parse current_approval_flow (pastikan di DB bertipe JSON string)
                $currentFlow = $pengajuan->current_approval_flow;

                // Jika tidak mengandung dosen_pa atau kaprodi → langsung skip
                if (!in_array('dosen_pa', $currentFlow) && !in_array('kaprodi', $currentFlow)) {
                    return false;
                }


                // CEK APAKAH DIA APPROVER AKTIF
                if (
                    !$pengajuan->approved_by_dosen_pa &&
                    $user->canApproveAsDosenPA($pengajuan->tahun_angkatan, $pengajuan->prodi_id)
                ) {
                    return true;
                }

                if (
                    $pengajuan->approved_by_dosen_pa && // Sudah approved oleh dosen_pa
                    !$pengajuan->approved_by_kaprodi &&
                    $user->canApproveAsKaprodi($pengajuan->tahun_angkatan, $pengajuan->prodi_id)
                ) {
                    return true;
                }

                if (
                    $pengajuan->approved_by_kaprodi &&
                    !$pengajuan->approved_by_wadek1 &&
                    $user->hasRole('wadek1')
                ) {
                    return true;
                }

                if (
                    $pengajuan->approved_by_wadek1 &&
                    !$pengajuan->approved_by_staff_tu &&
                    $user->hasRole('tu')
                ) {
                    return true;
                }

                if (
                    $pengajuan->approved_by_staff_tu &&
                    !$pengajuan->approved_by_bak &&
                    $user->hasRole('bak')
                ) {
                    return true;
                }

                // CEK APAKAH DIA MEMANG YANG SUDAH APPROVE (HISTORI)
                if (
                    $pengajuan->approved_by_dosen_pa == $login_id ||
                    $pengajuan->approved_by_kaprodi == $login_id ||
                    $pengajuan->approved_by_wadek1 == $login_id ||
                    $pengajuan->approved_by_staff_tu == $login_id ||
                    $pengajuan->approved_by_bak == $login_id
                ) {
                    return true;
                }

                return false;
            });

        return view('admin.pengajuan_surat.role.index', compact('pengajuanSurats'));
    }



    // ================= Approval Methods (unchanged) =================

    private function updateStatusAfterApproval(PengajuanSurat $pengajuan, $currentApprover = null)
    {
        $requiredApprovals = [
            'approved_at_dosen_pa',
            'approved_at_kaprodi',
            'approved_at_wadek1',
            'approved_at_staff_tu',
        ];

        $otherApprovals = $currentApprover
            ? array_filter($requiredApprovals, fn($field) => $field !== $currentApprover)
            : $requiredApprovals;

        $isAllApproved = true;
        foreach ($otherApprovals as $field) {
            if (empty($pengajuan->$field)) {
                $isAllApproved = false;
                break;
            }
        }

        if ($isAllApproved) {
            $pengajuan->status = 'disetujui';
        } elseif ($pengajuan->status === 'diajukan') {
            $pengajuan->status = 'diproses';
        }

        $pengajuan->save();
    }

    public function approveDouble($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_dosen_pa && $pengajuan->approved_at_kaprodi) {
            return redirect()->back()->with('error', 'Sudah disetujui oleh Dosen PA dan Kaprodi sebelumnya.');
        }

        $pengajuan->approved_by_dosen_pa = Auth::id();
        $pengajuan->approved_at_dosen_pa = now();
        $pengajuan->approved_by_kaprodi = Auth::id();
        $pengajuan->approved_at_kaprodi = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, null);

        return redirect()->back()->with('success', 'Disetujui oleh Dosen PA & Kaprodi.');
    }

    public function approveDosenPA($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_dosen_pa) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_dosen_pa = Auth::id();
        $pengajuan->approved_at_dosen_pa = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_dosen_pa');

        return redirect()->back()->with('success', 'Disetujui oleh Dosen PA.');
    }

    public function approveKaprodi($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_kaprodi) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_kaprodi = Auth::id();
        $pengajuan->approved_at_kaprodi = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_kaprodi');

        return redirect()->back()->with('success', 'Disetujui oleh Kaprodi.');
    }

    public function approveWadek1($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_wadek1) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_wadek1 = Auth::id();
        $pengajuan->approved_at_wadek1 = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_wadek1');

        return redirect()->back()->with('success', 'Disetujui oleh Wadek 1.');
    }

    public function approveStaffTU($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_staff_tu) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_staff_tu = Auth::id();
        $pengajuan->approved_at_staff_tu = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_staff_tu');

        return redirect()->back()->with('success', 'Disetujui oleh Staff TU.');
    }

    // ================= Reject Methods (unchanged) =================

    public function rejectDosenPA(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_dosen_pa = null;
        $pengajuan->approved_at_dosen_pa = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Dosen PA: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Dosen PA.');
    }

    public function rejectDouble(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_at_kaprodi = null;
        $pengajuan->approved_at_dosen_pa = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Dosen PA / Kaprodi: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Dosen PA.');
    }

    public function rejectKaprodi(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_kaprodi = null;
        $pengajuan->approved_at_kaprodi = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Kaprodi: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kaprodi.');
    }

    public function rejectWadek1(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_wadek1 = null;
        $pengajuan->approved_at_wadek1 = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Wadek 1: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Wadek 1.');
    }

    public function rejectStaffTU(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_staff_tu = null;
        $pengajuan->approved_at_staff_tu = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh Staff TU: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Staff TU.');
    }

    public function uploadSurat(Request $request, $id)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'file_surat' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);

        // Check if user has TU or BAK role
        if (!Auth::user()->hasRole(['tu', 'bak'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk upload surat.');
        }

        try {
            // Store the file
            $filePath = $request->file('file_surat')->store('surat_files', 'public');

            // Create or update file approval
            \App\Models\FileApproval::updateOrCreate(
                ['id_pengajuan' => $pengajuan->id],
                [
                    'nomor_surat' => $request->nomor_surat,
                    'file_surat' => $filePath
                ]
            );

            return redirect()->back()->with('success', 'Surat berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Error uploading surat', [
                'message' => $e->getMessage(),
                'pengajuan_id' => $id
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat upload surat.');
        }
    }

    // ================= Dynamic Approval Methods =================

    /**
     * Generic approve method for dynamic workflow
     */
    public function approveDynamic(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        $user = Auth::user();

        // Get current step
        $currentStep = $pengajuan->current_step;

        if (!$currentStep) {
            return redirect()->back()->with('error', 'Pengajuan sudah selesai diproses.');
        }

        // Check if user can approve current step
        if (!$pengajuan->canBeApprovedBy($user)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui pengajuan ini.');
        }

        // Check if already approved
        if ($pengajuan->isApprovedByStep($currentStep)) {
            return redirect()->back()->with('error', 'Pengajuan sudah disetujui sebelumnya.');
        }

        // Approve the pengajuan
        $pengajuan->approveByStep($currentStep, $user->id);

        // Get step display name
        $stepNames = [
            'dosen_pa' => 'Dosen PA',
            'kaprodi' => 'Kaprodi',
            'wadek1' => 'Wadek 1',
            'tu' => 'TU',
            'bak' => 'BAK'
        ];

        $stepName = $stepNames[$currentStep] ?? $currentStep;

        return redirect()->back()->with('success', "Pengajuan berhasil disetujui oleh $stepName.");
    }

    /**
     * Generic reject method for dynamic workflow
     */
    public function rejectDynamic(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $user = Auth::user();

        // Get current step
        $currentStep = $pengajuan->current_step;

        if (!$currentStep) {
            return redirect()->back()->with('error', 'Pengajuan sudah selesai diproses.');
        }

        // Check if user can reject current step
        if (!$pengajuan->canBeApprovedBy($user)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak pengajuan ini.');
        }

        // Reject the pengajuan
        $pengajuan->rejectByStep($currentStep, $user->id, $request->alasan_reject);

        // Get step display name
        $stepNames = [
            'dosen_pa' => 'Dosen PA',
            'kaprodi' => 'Kaprodi',
            'wadek1' => 'Wadek 1',
            'tu' => 'TU',
            'bak' => 'BAK'
        ];

        $stepName = $stepNames[$currentStep] ?? $currentStep;

        return redirect()->back()->with('success', "Pengajuan berhasil ditolak oleh $stepName.");
    }

    /**
     * BAK specific approval methods
     */
    public function approveBAK($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        if ($pengajuan->approved_at_bak) {
            return redirect()->back()->with('error', 'Sudah disetujui sebelumnya.');
        }

        $pengajuan->approved_by_bak = Auth::id();
        $pengajuan->approved_at_bak = now();
        $pengajuan->save();

        $this->updateStatusAfterApproval($pengajuan, 'approved_at_bak');

        return redirect()->back()->with('success', 'Disetujui oleh BAK.');
    }

    public function rejectBAK(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        $pengajuan->approved_by_bak = null;
        $pengajuan->approved_at_bak = null;
        $pengajuan->status = 'ditolak';
        $pengajuan->keterangan = 'Ditolak oleh BAK: ' . $request->alasan_reject;
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh BAK.');
    }
}
