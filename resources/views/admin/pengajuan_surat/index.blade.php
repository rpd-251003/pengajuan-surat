@extends('layouts.default')

@section('content')
    <div class="container mt-4">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <strong>Peringatan!</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                </svg>
                <strong>Info!</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-body">
            <h3>Daftar Pengajuan Surat</h3>
            <p class="text-muted">Kelola semua pengajuan surat mahasiswa</p>

            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.pengajuan.index') }}">
                        <div class="input-group">
                            <select name="jenis_surat" class="form-select">
                                <option value="">Semua Jenis Surat</option>
                                {{-- @foreach($jenisSuratOptions as $option)
                                    <option value="{{ $option->id }}"
                                        {{ request('jenis_surat') == $option->id ? 'selected' : '' }}>
                                        {{ $option->nama }}
                                    </option>
                                @endforeach --}}
                            </select>
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.pengajuan.index') }}">
                        <div class="input-group">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <button class="btn btn-outline-success" type="submit">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i> Reset Filter
                    </a>
                </div>
            </div>

            <!-- Cards for displaying pengajuan -->
            <div class="row">
                @forelse ($pengajuanSurats as $p)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Left side - Main info -->
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-gradient rounded-circle p-3 me-3">
                                                <i class="fas fa-file-alt text-white fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $p->jenisSurat->nama ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $p->mahasiswa->user->name ?? 'N/A' }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    {{ $p->mahasiswa->nim ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status and timeline -->
                                    <div class="col-md-5">
                                        @php
                                            $statusClass = match($p->status) {
                                                'diajukan' => 'bg-warning',
                                                'diproses' => 'bg-primary',
                                                'disetujui' => 'bg-success',
                                                'ditolak' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp

                                        <div class="mb-2">
                                            <span class="badge {{ $statusClass }} fs-6">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </div>

                                        <!-- Timeline -->
                                        <div class="d-flex align-items-center" style="font-size: 11px;">
                                            <!-- Dosen PA -->
                                            <div class="text-center me-2">
                                                <div class="position-relative">
                                                    @if ($p->approved_at_dosen_pa)
                                                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 10px; height: 10px;">
                                                            <i class="fas fa-check text-white" style="font-size: 6px;"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-secondary rounded-circle" style="width: 10px; height: 10px;"></div>
                                                    @endif
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 9px;">PA</small>
                                            </div>

                                            <i class="fas fa-arrow-right text-muted me-2" style="font-size: 8px;"></i>

                                            <!-- Kaprodi -->
                                            <div class="text-center me-2">
                                                <div class="position-relative">
                                                    @if ($p->approved_at_kaprodi)
                                                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 10px; height: 10px;">
                                                            <i class="fas fa-check text-white" style="font-size: 6px;"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-secondary rounded-circle" style="width: 10px; height: 10px;"></div>
                                                    @endif
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 9px;">Kaprodi</small>
                                            </div>

                                            <i class="fas fa-arrow-right text-muted me-2" style="font-size: 8px;"></i>

                                            <!-- Wadek1 -->
                                            <div class="text-center me-2">
                                                <div class="position-relative">
                                                    @if ($p->approved_at_wadek1)
                                                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 10px; height: 10px;">
                                                            <i class="fas fa-check text-white" style="font-size: 6px;"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-secondary rounded-circle" style="width: 10px; height: 10px;"></div>
                                                    @endif
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 9px;">Wadek1</small>
                                            </div>

                                            <i class="fas fa-arrow-right text-muted me-2" style="font-size: 8px;"></i>

                                            <!-- Staff TU -->
                                            <div class="text-center">
                                                <div class="position-relative">
                                                    @if ($p->approved_at_staff_tu)
                                                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 10px; height: 10px;">
                                                            <i class="fas fa-check text-white" style="font-size: 6px;"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-secondary rounded-circle" style="width: 10px; height: 10px;"></div>
                                                    @endif
                                                </div>
                                                <small class="d-block mt-1" style="font-size: 9px;">TU</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Detail Button -->
                                            @php
                                                $details = $p->getDetailsArray();
                                            @endphp
                                            @if (!empty($details) || $p->keterangan)
                                                <button type="button" class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $p->id }}">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            @endif

                                            <!-- Role-based Actions -->
                                            @if ($p->status !== 'ditolak')
                                                @php
                                                    $canApproveAsDosenPA = Auth::user()->canApproveAsDosenPA($p->tahun_angkatan, $p->prodi_id);
                                                    $canApproveAsKaprodi = Auth::user()->canApproveAsKaprodi($p->tahun_angkatan, $p->prodi_id);
                                                    $canApproveDouble = $canApproveAsDosenPA && $canApproveAsKaprodi;
                                                @endphp

                                                <!-- Dosen PA & Kaprodi Actions -->
                                                @if ($canApproveDouble && !$p->approved_at_dosen_pa && !$p->approved_at_kaprodi)
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        onclick="confirmApprove('{{ route('admin.pengajuan.approve_double', $p->id) }}')">
                                                        <i class="fas fa-check-double me-1"></i> Approve PA & Kaprodi
                                                    </button>
                                                @else
                                                    @if ($canApproveAsDosenPA && !$p->approved_at_dosen_pa)
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_dosen_pa', $p->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve PA
                                                        </button>
                                                    @endif

                                                    @if ($canApproveAsKaprodi && !$p->approved_at_kaprodi)
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_kaprodi', $p->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve Kaprodi
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Wadek1 Actions -->
                                                @if (Auth::user()->hasRole('wadek1') && !$p->approved_at_wadek1 && $p->approved_at_dosen_pa && $p->approved_at_kaprodi)
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        onclick="confirmApprove('{{ route('admin.pengajuan.approve_wadek1', $p->id) }}')">
                                                        <i class="fas fa-check me-1"></i> Approve Wadek1
                                                    </button>
                                                @endif

                                                <!-- Staff TU Actions -->
                                                @if (Auth::user()->hasRole('tu'))
                                                    @if (!$p->approved_at_staff_tu && $p->approved_at_wadek1)
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_staff_tu', $p->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve TU
                                                        </button>
                                                    @endif

                                                    @if (!$p->fileApproval && $p->approved_at_wadek1)
                                                        <!-- Upload Surat Button -->
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#uploadModal{{ $p->id }}">
                                                            <i class="fas fa-upload me-1"></i> Upload Surat
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                            data-bs-target="#viewModal{{ $p->id }}">
                                                            <i class="fas fa-eye me-1"></i> Lihat Surat
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Reject Button for all roles that can approve -->
                                                @if (($canApproveAsDosenPA && !$p->approved_at_dosen_pa) ||
                                                     ($canApproveAsKaprodi && !$p->approved_at_kaprodi) ||
                                                     (Auth::user()->hasRole('wadek1') && !$p->approved_at_wadek1 && $p->approved_at_dosen_pa && $p->approved_at_kaprodi) ||
                                                     (Auth::user()->hasRole('tu') && !$p->approved_at_staff_tu && $p->approved_at_wadek1))
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="showRejectModal('{{ $p->id }}')">
                                                        <i class="fas fa-times me-1"></i> Tolak
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-inbox mb-2" viewBox="0 0 16 16">
                                <path
                                    d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438L14.933 9zM3.809 3.563A1.5 1.5 0 0 1 4.981 3h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                            </svg>
                            <h5>Tidak ada pengajuan surat</h5>
                            <p class="mb-0">Belum ada pengajuan surat yang sesuai dengan filter yang dipilih.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $pengajuanSurats->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modals -->
    @foreach ($pengajuanSurats as $pengajuan)
        <div class="modal fade" id="detailModal{{ $pengajuan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-alt me-2"></i>
                            Detail Pengajuan: {{ $pengajuan->jenisSurat->nama ?? 'Belum ditentukan' }}<br>
                            <small class="text-muted">
                                Pengajuan #{{ $pengajuan->id }} - {{ $pengajuan->mahasiswa->user->name ?? 'N/A' }}
                            </small>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Info Pengajuan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i> Informasi Umum</h6>
                                        <div class="mb-2">
                                            <strong>Status:</strong>
                                            @php
                                                $statusClass = match($pengajuan->status) {
                                                    'diajukan' => 'bg-warning',
                                                    'diproses' => 'bg-primary',
                                                    'disetujui' => 'bg-success',
                                                    'ditolak' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst($pengajuan->status) }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? 'N/A' }}
                                        </div>
                                        @if ($pengajuan->keterangan)
                                            <div class="mb-2">
                                                <strong>Keterangan:</strong><br>
                                                <div class="bg-white p-2 rounded border">
                                                    {!! nl2br(e($pengajuan->keterangan)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-2"><i class="fas fa-clock me-1"></i> Status Approval</h6>
                                        <div class="mb-2">
                                            <strong>Dosen PA:</strong>
                                            @if ($pengajuan->approved_at_dosen_pa)
                                                <span class="badge bg-success">✓ Disetujui</span><br>
                                                <small class="text-muted">
                                                    {{ $pengajuan->dosenPA->name ?? 'N/A' }} -
                                                    {{ \Carbon\Carbon::parse($pengajuan->approved_at_dosen_pa)->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Kaprodi:</strong>
                                            @if ($pengajuan->approved_at_kaprodi)
                                                <span class="badge bg-success">✓ Disetujui</span><br>
                                                <small class="text-muted">
                                                    {{ $pengajuan->kaprodi->name ?? 'N/A' }} -
                                                    {{ \Carbon\Carbon::parse($pengajuan->approved_at_kaprodi)->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Wadek1:</strong>
                                            @if ($pengajuan->approved_at_wadek1)
                                                <span class="badge bg-success">✓ Disetujui</span><br>
                                                <small class="text-muted">
                                                    {{ $pengajuan->wadek1->name ?? 'N/A' }} -
                                                    {{ \Carbon\Carbon::parse($pengajuan->approved_at_wadek1)->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Staff TU:</strong>
                                            @if ($pengajuan->approved_at_staff_tu)
                                                <span class="badge bg-success">✓ Selesai</span><br>
                                                <small class="text-muted">
                                                    {{ $pengajuan->staffTU->name ?? 'N/A' }} -
                                                    {{ \Carbon\Carbon::parse($pengajuan->approved_at_staff_tu)->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <span class="badge bg-warning">Menunggu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Fields -->
                        @if ($pengajuan->jenisSurat && $pengajuan->jenisSurat->fields)
                            @php
                                $details = $pengajuan->getDetailsArray();
                            @endphp
                            @if (!empty($details))
                                <h6 class="mb-3"><i class="fas fa-list me-2"></i> Detail Pengajuan</h6>
                                <div class="row">
                                    @foreach ($pengajuan->jenisSurat->fields as $field)
                                        @php
                                            $value = $details[$field->field_name] ?? '';
                                        @endphp
                                        @if ($value)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-0 bg-light">
                                                    <div class="card-body p-3">
                                                        <strong>{{ $field->field_label }}:</strong>
                                                        <div class="mt-1">
                                                            @if ($field->field_type === 'file')
                                                                @php
                                                                    $fileInfo = is_array($value) ? $value : json_decode($value, true);
                                                                @endphp
                                                                @if ($fileInfo && isset($fileInfo['path']))
                                                                    <div class="d-flex align-items-center">
                                                                        @php
                                                                            $extension = pathinfo($fileInfo['original_name'] ?? '', PATHINFO_EXTENSION);
                                                                            $iconClass = match(strtolower($extension)) {
                                                                                'pdf' => 'fas fa-file-pdf text-danger',
                                                                                'doc', 'docx' => 'fas fa-file-word text-primary',
                                                                                'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                                                                'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-info',
                                                                                'zip', 'rar' => 'fas fa-file-archive text-warning',
                                                                                default => 'fas fa-file text-secondary'
                                                                            };
                                                                        @endphp
                                                                        <i class="{{ $iconClass }} fa-2x me-3"></i>
                                                                        <div>
                                                                            <a href="{{ route('pengajuan_surat.download_file', [$pengajuan->id, $field->field_name]) }}"
                                                                               class="text-decoration-none fw-bold">
                                                                                {{ $fileInfo['original_name'] ?? 'File' }}
                                                                            </a>
                                                                            @if (isset($fileInfo['size']))
                                                                                <small class="text-muted d-block">
                                                                                    <i class="fas fa-hdd me-1"></i>
                                                                                    {{ number_format($fileInfo['size'] / 1024, 2) }} KB
                                                                                </small>
                                                                            @endif
                                                                            <small class="text-muted d-block">
                                                                                <i class="fas fa-download me-1"></i>
                                                                                Klik untuk download
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">
                                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                                        File tidak tersedia
                                                                    </span>
                                                                @endif
                                                            @elseif ($field->field_type === 'textarea')
                                                                <div class="bg-white p-2 rounded border">
                                                                    {!! nl2br(e($value)) !!}
                                                                </div>
                                                            @elseif ($field->field_type === 'select')
                                                                <span class="badge bg-info">{{ $value }}</span>
                                                            @elseif ($field->field_type === 'checkbox')
                                                                @if (is_array($value))
                                                                    @foreach ($value as $item)
                                                                        <span class="badge bg-secondary me-1">{{ $item }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary">{{ $value }}</span>
                                                                @endif
                                                            @else
                                                                <div class="text-dark">{{ $value }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        <!-- File Surat yang sudah diupload -->
                        @if ($pengajuan->fileApproval)
                            <div class="mt-4">
                                <h6 class="mb-3"><i class="fas fa-file-pdf me-2"></i> File Surat</h6>
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                            <div>
                                                <strong>{{ $pengajuan->fileApproval->nomor_surat }}</strong><br>
                                                <small class="text-muted">
                                                    Diupload: {{ $pengajuan->fileApproval->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                   target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Upload Modal for Staff TU -->
    @foreach ($pengajuanSurats as $p)
        @if (Auth::user()->hasRole('tu'))
            <!-- Modal Upload Surat -->
            <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1"
                aria-labelledby="uploadModalLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.pengajuan.upload_surat', $p->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadModalLabel{{ $p->id }}">
                                    Upload Surat - {{ $p->mahasiswa->user->name ?? 'N/A' }}
                                    <small class="text-muted d-block">{{ $p->jenisSurat->nama ?? 'N/A' }}</small>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nomor_surat" class="form-label">
                                        <i class="fas fa-file-alt me-1 text-primary"></i> Nomor Surat
                                    </label>
                                    <input type="text" class="form-control" name="nomor_surat" id="nomor_surat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="file_surat" class="form-label">
                                        <i class="fas fa-upload me-1 text-success"></i> Upload Surat
                                    </label>
                                    <input type="file" class="form-control" name="file_surat" id="file_surat" accept=".pdf,.doc,.docx" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Upload Surat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal View Surat -->
            <div class="modal fade" id="viewModal{{ $p->id }}" tabindex="-1"
                aria-labelledby="viewModalLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel{{ $p->id }}">
                                Surat - Pengajuan #{{ $p->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($p->fileApproval)
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                    </div>
                                    <h6>{{ $p->fileApproval->nomor_surat }}</h6>
                                    <p class="text-muted">{{ $p->jenisSurat->nama ?? 'N/A' }}</p>
                                    <a href="{{ asset('storage/' . $p->fileApproval->file_surat) }}"
                                       target="_blank" class="btn btn-primary">
                                        <i class="fas fa-download me-2"></i>Download Surat
                                    </a>
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h6>File surat belum tersedia</h6>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Approval confirmation modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui pengajuan surat ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="approveForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" name="reject_reason" id="reject_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmApprove(url) {
            document.getElementById('approveForm').action = url;
            new bootstrap.Modal(document.getElementById('approveModal')).show();
        }

        function showRejectModal(pengajuanId) {
            // Determine reject route based on user role and current approval status
            let rejectUrl = '';

            @if(Auth::user()->hasRole('tu'))
                rejectUrl = '{{ url("/data/pengajuan") }}/' + pengajuanId + '/reject_staff_tu';
            @elseif(Auth::user()->hasRole('wadek1'))
                rejectUrl = '{{ url("/data/pengajuan") }}/' + pengajuanId + '/reject_wadek1';
            @elseif(Auth::user()->hasRole('kaprodi'))
                rejectUrl = '{{ url("/data/pengajuan") }}/' + pengajuanId + '/reject_kaprodi';
            @else
                // For dosen or dual role
                rejectUrl = '{{ url("/data/pengajuan") }}/' + pengajuanId + '/reject_dosen_pa';
            @endif

            document.getElementById('rejectForm').action = rejectUrl;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 5000);
            });
        });
    </script>
@endpush
