@extends('layouts.default')

@section('content')
    <div class="container mt-4">
        @if (session('success') || session('error') || session('warning') || session('info'))
            <div class="container mt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Peringatan!</strong> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Info!</strong> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        @endif
        <div class="card card-body">
            <h3>Daftar Pengajuan Surat</h3>

            <!-- Search & Filter -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchInput"
                        placeholder="Cari nama mahasiswa atau jenis surat...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="partial">Sebagian Approved</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="jenisFilter">
                        <option value="">Semua Jenis Surat</option>
                        @foreach ($pengajuanSurats->unique('jenisSurat.nama') as $p)
                            <option value="{{ $p->jenisSurat->nama ?? '' }}">{{ $p->jenisSurat->nama ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">Reset</button>
                </div>
            </div>

            <!-- Cards Container -->
            <div class="row" id="cardsContainer">
                @foreach ($pengajuanSurats as $index => $p)
                    @php
                        $statusCount = 0;
                        if ($p->approved_at_dosen_pa) {
                            $statusCount++;
                        }
                        if ($p->approved_at_kaprodi) {
                            $statusCount++;
                        }
                        if ($p->approved_at_wadek1) {
                            $statusCount++;
                        }
                        if ($p->approved_at_staff_tu) {
                            $statusCount++;
                        }

                        $overallStatus = $statusCount == 0 ? 'pending' : ($statusCount == 4 ? 'approved' : 'partial');
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 card-item"
                        data-mahasiswa="{{ strtolower($p->mahasiswa->user->name ?? '') }}"
                        data-jenis="{{ strtolower($p->jenisSurat->nama ?? '') }}" data-status="{{ $overallStatus }}">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <!-- Card Header with Gradient -->
                            <div class="card-header bg-gradient-primary text-white border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-light">{{ $p->mahasiswa->user->name ?? 'N/A' }}</h6>
                                        <small class="opacity-85">
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            {{ $p->jenisSurat->nama ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-white text-primary badge-status">
                                            @if ($overallStatus == 'approved')
                                                <i class="bi bi-check-circle-fill me-1"></i>Selesai
                                            @elseif($overallStatus == 'partial')
                                                <i class="bi bi-clock-fill me-1"></i>Proses
                                            @else
                                                <i class="bi bi-hourglass-split me-1"></i>Pending
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <!-- Tanggal Pengajuan -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        <small>Diajukan:
                                            {{ $p->created_at ? $p->created_at : 'N/A' }}</small>
                                    </div>
                                </div>

                                <!-- Status Timeline Enhanced -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted fw-semibold">Progress Persetujuan</small>
                                        <span class="badge bg-light text-dark">{{ $statusCount }}/4</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="progress mb-2" style="height: 4px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ ($statusCount / 4) * 100 }}%"></div>
                                    </div>

                                    <!-- Status Dots -->
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="status-step {{ $p->approved_at_dosen_pa ? 'completed' : 'pending' }}"
                                                title="Dosen PA{{ $p->approved_at_dosen_pa ? ' ✓' : '' }}">
                                                @if ($p->approved_at_dosen_pa)
                                                    <i class="bi bi-check2"></i>
                                                @else
                                                    <span class="step-number">1</span>
                                                @endif
                                            </div>
                                            <div class="status-connector {{ $p->approved_at_dosen_pa ? 'active' : '' }}">
                                            </div>

                                            <div class="status-step {{ $p->approved_at_kaprodi ? 'completed' : 'pending' }}"
                                                title="Kaprodi{{ $p->approved_at_kaprodi ? ' ✓' : '' }}">
                                                @if ($p->approved_at_kaprodi)
                                                    <i class="bi bi-check2"></i>
                                                @else
                                                    <span class="step-number">2</span>
                                                @endif
                                            </div>
                                            <div class="status-connector {{ $p->approved_at_kaprodi ? 'active' : '' }}">
                                            </div>

                                            <div class="status-step {{ $p->approved_at_wadek1 ? 'completed' : 'pending' }}"
                                                title="Wadek 1{{ $p->approved_at_wadek1 ? ' ✓' : '' }}">
                                                @if ($p->approved_at_wadek1)
                                                    <i class="bi bi-check2"></i>
                                                @else
                                                    <span class="step-number">3</span>
                                                @endif
                                            </div>
                                            <div class="status-connector {{ $p->approved_at_wadek1 ? 'active' : '' }}">
                                            </div>

                                            <div class="status-step {{ $p->approved_at_staff_tu ? 'completed' : 'pending' }}"
                                                title="Staff TU{{ $p->approved_at_staff_tu ? ' ✓' : '' }}">
                                                @if ($p->approved_at_staff_tu)
                                                    <i class="bi bi-check2"></i>
                                                @else
                                                    <span class="step-number">4</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Labels -->
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted status-label">PA</small>
                                        <small class="text-muted status-label">Kaprodi</small>
                                        <small class="text-muted status-label">Wadek</small>
                                        <small class="text-muted status-label">TU</small>
                                    </div>
                                </div>

                                <!-- Keterangan dengan styling yang lebih baik -->
                                <div class="mb-3">
                                    <div class="keterangan-box p-2 bg-light rounded">
                                        <small class="text-muted fw-semibold d-block mb-1">
                                            <i class="bi bi-chat-text me-1"></i>Keterangan:
                                        </small>
                                        <p class="mb-0 text-dark">
                                            @php
                                                $keterangan = $p->keterangan ?? '';
                                                $shortKeterangan = Str::limit($keterangan, 100);
                                            @endphp
                                            {!! nl2br(e($shortKeterangan)) !!}
                                            @if (strlen($keterangan) > 100)
                                                <button class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold"
                                                    data-bs-toggle="modal" data-bs-target="#keteranganModal"
                                                    data-keterangan="{{ $keterangan }}"
                                                    data-mahasiswa="{{ $p->mahasiswa->user->name ?? 'N/A' }}">
                                                    Selengkapnya...
                                                </button>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Detail Button -->
                                <div class="mb-3">
                                    @php
                                        $details = $p->getDetailsArray();
                                    @endphp
                                    @if (!empty($details) || $p->keterangan)
                                        <button type="button" class="btn btn-outline-info btn-sm w-100"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->id }}">
                                            <i class="bi bi-eye me-1"></i> Lihat Detail Lengkap
                                        </button>
                                    @else
                                        <div class="text-center">
                                            <small class="text-muted">Tidak ada detail tambahan</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer dengan styling yang lebih baik -->
                            <div class="card-footer bg-white border-top">
                                @php $id_user = Auth::user()->id; @endphp

                                <div class="d-flex gap-2">
                                    @if (
                                        $p->approved_by_kaprodi == $id_user &&
                                            $p->approved_by_dosen_pa == $id_user &&
                                            $p->approved_at_kaprodi != null &&
                                            $p->approved_at_dosen_pa != null)
                                        {{-- Sudah diapprove oleh kedua role --}}
                                        <div class="text-center w-100">
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Approved Already
                                            </span>
                                        </div>
                                    @elseif($p->approved_by_kaprodi == $id_user && $p->approved_by_dosen_pa == $id_user)
                                        {{-- User adalah kedua role tapi belum approve --}}
                                        <form method="POST"
                                            action="{{ route('admin.pengajuan.approve_double', $p->id) }}"
                                            class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold"
                                                onclick="return confirm('Yakin approve sebagai Kaprodi & Dosen PA?')">
                                                <i class="bi bi-check-lg me-1"></i>Approve as Kaprodi & Dosen PA
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-reject ms-2"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal"
                                            data-id="{{ $p->id }}" data-level="double" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @elseif($p->approved_by_kaprodi == $id_user && $p->approved_at_kaprodi == null)
                                        {{-- User adalah Kaprodi dan belum approve --}}
                                        <form method="POST"
                                            action="{{ route('admin.pengajuan.approve_kaprodi', $p->id) }}"
                                            class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold"
                                                onclick="return confirm('Yakin approve sebagai Kaprodi?')">
                                                <i class="bi bi-check-lg me-1"></i>Approve as Kaprodi
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-reject ms-2"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal"
                                            data-id="{{ $p->id }}" data-level="kaprodi" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @elseif($p->approved_by_dosen_pa == $id_user && $p->approved_at_dosen_pa == null)
                                        {{-- User adalah Dosen PA dan belum approve --}}
                                        <form method="POST"
                                            action="{{ route('admin.pengajuan.approve_dosen_pa', $p->id) }}"
                                            class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold"
                                                onclick="return confirm('Yakin approve sebagai Dosen PA?')">
                                                <i class="bi bi-check-lg me-1"></i>Approve as Dosen PA
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-reject ms-2"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal"
                                            data-id="{{ $p->id }}" data-level="dosen_pa" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @elseif($p->approved_by_kaprodi == $id_user && $p->approved_at_kaprodi != null)
                                        {{-- Kaprodi sudah approve --}}
                                        <div class="text-center w-100">
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-check-circle me-1"></i>Approved as Kaprodi
                                            </span>
                                        </div>
                                    @elseif($p->approved_by_dosen_pa == $id_user && $p->approved_at_dosen_pa != null)
                                        {{-- Dosen PA sudah approve --}}
                                        <div class="text-center w-100">
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-check-circle me-1"></i>Approved as Dosen PA
                                            </span>
                                        </div>
                                    @else
                                        {{-- Tidak ada aksi tersedia --}}
                                        <div class="text-center w-100">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>Tidak ada aksi tersedia
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="text-center mt-4" style="display: none;">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Tidak ada data yang sesuai dengan filter yang dipilih.
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Keterangan Lengkap -->
    <div class="modal fade" id="keteranganModal" tabindex="-1" aria-labelledby="keteranganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="keteranganModalLabel">Keterangan Lengkap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="modalMahasiswaName"></h6>
                    <div id="modalKeterangan"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                            <label for="alasan_reject" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" name="alasan_reject" id="alasan_reject" rows="3" required></textarea>
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
                                                $statusCount = 0;
                                                if ($pengajuan->approved_at_dosen_pa) {
                                                    $statusCount++;
                                                }
                                                if ($pengajuan->approved_at_kaprodi) {
                                                    $statusCount++;
                                                }
                                                if ($pengajuan->approved_at_wadek1) {
                                                    $statusCount++;
                                                }
                                                if ($pengajuan->approved_at_staff_tu) {
                                                    $statusCount++;
                                                }

                                                $overallStatus =
                                                    $statusCount == 0
                                                        ? 'pending'
                                                        : ($statusCount == 4
                                                            ? 'approved'
                                                            : 'partial');
                                                $statusClass = match ($overallStatus) {
                                                    'pending' => 'bg-warning',
                                                    'partial' => 'bg-primary',
                                                    'approved' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                                $statusText = match ($overallStatus) {
                                                    'pending' => 'Pending',
                                                    'partial' => 'Diproses',
                                                    'approved' => 'Disetujui',
                                                    default => 'Unknown',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Progress:</strong> {{ $statusCount }}/4 Tahap
                                        </div>
                                        <div class="mb-2">
                                            <strong>Tanggal Pengajuan:</strong>
                                            {{ $pengajuan->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>NIM:</strong> {{ $pengajuan->mahasiswa->user->nomor_identifikasi ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Prodi:</strong> {{ $pengajuan->mahasiswa->prodi->nama ?? 'N/A' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Angkatan:</strong> {{ $pengajuan->mahasiswa->angkatan ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-2"><i class="fas fa-tasks me-1"></i> Status Persetujuan</h6>

                                        <!-- Dosen PA -->
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                            style="background-color: {{ $pengajuan->approved_at_dosen_pa ? '#d4edda' : '#f8d7da' }}">
                                            <div>
                                                <strong>Dosen PA:</strong><br>
                                                <small
                                                    class="text-muted">{{ $pengajuan->dosenPa->name ?? 'Belum ditentukan' }}</small>
                                            </div>
                                            <div>
                                                @if ($pengajuan->approved_at_dosen_pa)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                    <br><small>{{ $pengajuan->approved_at_dosen_pa }}</small>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Kaprodi -->
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                            style="background-color: {{ $pengajuan->approved_at_kaprodi ? '#d4edda' : '#f8d7da' }}">
                                            <div>
                                                <strong>Kaprodi:</strong><br>
                                                <small
                                                    class="text-muted">{{ $pengajuan->kaprodi->name ?? 'Belum ditentukan' }}</small>
                                            </div>
                                            <div>
                                                @if ($pengajuan->approved_at_kaprodi)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                    <br><small>{{ $pengajuan->approved_at_kaprodi }}</small>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Wadek 1 -->
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                            style="background-color: {{ $pengajuan->approved_at_wadek1 ? '#d4edda' : '#f8d7da' }}">
                                            <div>
                                                <strong>Wadek 1:</strong><br>
                                                <small
                                                    class="text-muted">{{ $pengajuan->wadek1->name ?? 'Belum ditentukan' }}</small>
                                            </div>
                                            <div>
                                                @if ($pengajuan->approved_at_wadek1)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                    <br><small>{{ $pengajuan->approved_at_wadek1 }}</small>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Staff TU -->
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                            style="background-color: {{ $pengajuan->approved_at_staff_tu ? '#d4edda' : '#f8d7da' }}">
                                            <div>
                                                <strong>Staff TU:</strong><br>
                                                <small
                                                    class="text-muted">{{ $pengajuan->staffTu->name ?? 'Belum ditentukan' }}</small>
                                            </div>
                                            <div>
                                                @if ($pengajuan->approved_at_staff_tu)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                    <br><small>{{ $pengajuan->approved_at_staff_tu }}</small>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Approval Section - Highlighted -->
                        @if ($pengajuan->fileApproval && $pengajuan->fileApproval->file_surat)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-success bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 text-success"><i class="fas fa-file-download me-1"></i> File
                                                Surat Tersedia</h6>
                                            <div
                                                class="d-flex align-items-center p-3 bg-white rounded border border-success">
                                                <div class="me-3">
                                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold h6 mb-1">
                                                        {{ $pengajuan->jenisSurat->nama ?? 'Surat Resmi' }}</div>
                                                    <small class="text-muted">File surat yang telah disetujui dan siap
                                                        diunduh</small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Dibuat:
                                                        @if ($pengajuan->fileApproval->created_at)
                                                            @if (is_string($pengajuan->fileApproval->created_at))
                                                                {{ $pengajuan->fileApproval->created_at }}
                                                            @else
                                                                {{ $pengajuan->fileApproval->created_at }}
                                                            @endif
                                                        @else
                                                            N/A
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                        target="_blank" class="btn btn-success btn-lg me-2">
                                                        <i class="fas fa-eye me-1"></i>
                                                        Lihat
                                                    </a>
                                                    <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                        download class="btn btn-primary btn-lg">
                                                        <i class="fas fa-download me-1"></i>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-warning bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="mb-2 text-warning"><i class="fas fa-exclamation-triangle me-1"></i>
                                                File Surat</h6>
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                File surat belum tersedia. Surat akan tersedia setelah semua tahap
                                                persetujuan selesai.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Keterangan -->
                        @if ($pengajuan->keterangan)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="mb-2"><i class="fas fa-comment me-1"></i> Keterangan</h6>
                                            <div class="bg-white p-3 rounded border">
                                                {!! nl2br(e($pengajuan->keterangan)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Detail Pengajuan -->
                        @if ($pengajuan->jenisSurat && $pengajuan->jenisSurat->fields)
                            @php
                                $details = $pengajuan->getDetailsArray();
                            @endphp
                            @if (!empty($details))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="mb-3"><i class="fas fa-list me-2"></i> Detail Pengajuan</h6>
                                                @foreach ($pengajuan->jenisSurat->fields as $field)
                                                    @php
                                                        $value = $details[$field->field_name] ?? '';
                                                    @endphp
                                                    @if ($value)
                                                        <div class="mb-3 p-3 bg-white rounded border">
                                                            <strong
                                                                class="d-block mb-2">{{ $field->field_label }}:</strong>
                                                            <div class="mt-1">
                                                                @if ($field->field_type === 'file')
                                                                    @php
                                                                        $fileInfo = is_array($value)
                                                                            ? $value
                                                                            : json_decode($value, true);
                                                                    @endphp
                                                                    @if ($fileInfo && isset($fileInfo['original_name']))
                                                                        <div
                                                                            class="d-flex align-items-center p-3 border rounded bg-light">
                                                                            <div class="me-3">
                                                                                @php
                                                                                    $ext = strtolower(
                                                                                        pathinfo(
                                                                                            $fileInfo['original_name'],
                                                                                            PATHINFO_EXTENSION,
                                                                                        ),
                                                                                    );
                                                                                    $icon = match ($ext) {
                                                                                        'pdf'
                                                                                            => 'fas fa-file-pdf text-danger',
                                                                                        'doc',
                                                                                        'docx'
                                                                                            => 'fas fa-file-word text-primary',
                                                                                        'jpg',
                                                                                        'jpeg',
                                                                                        'png'
                                                                                            => 'fas fa-file-image text-info',
                                                                                        default
                                                                                            => 'fas fa-file text-secondary',
                                                                                    };
                                                                                @endphp
                                                                                <i class="{{ $icon }}"
                                                                                    style="font-size: 2rem;"></i>
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <div class="fw-bold text-dark">
                                                                                    {{ $fileInfo['original_name'] }}</div>
                                                                                <small class="text-muted">
                                                                                    <i
                                                                                        class="fas fa-weight me-1"></i>{{ number_format($fileInfo['size'] / 1024, 2) }}
                                                                                    KB
                                                                                    <span class="mx-2">•</span>
                                                                                    <i class="fas fa-calendar me-1"></i>
                                                                                    {{ $fileInfo['uploaded_at'] }}

                                                                                </small>
                                                                            </div>
                                                                            <div class="text-end">
                                                                                <a href="{{ route('pengajuan.file.view', [$pengajuan->id, $field->field_name]) }}"
                                                                                    target="_blank"
                                                                                    class="btn btn-sm btn-outline-primary me-2"
                                                                                    title="Buka file">
                                                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                                                </a>
                                                                                <a href="{{ route('pengajuan.file.download', [$pengajuan->id, $field->field_name]) }}"
                                                                                    class="btn btn-sm btn-outline-success"
                                                                                    title="Download">
                                                                                    <i class="fas fa-download me-1"></i>
                                                                                    Download
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted">File tidak tersedia</span>
                                                                    @endif
                                                                @elseif ($field->field_type === 'checkbox')
                                                                    @php
                                                                        $selectedValues = is_string($value)
                                                                            ? json_decode($value, true)
                                                                            : $value;
                                                                        $selectedValues = is_array($selectedValues)
                                                                            ? $selectedValues
                                                                            : [$value];
                                                                    @endphp
                                                                    @if ($field->field_options)
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            @foreach ($selectedValues as $selectedValue)
                                                                                @if (isset($field->field_options[$selectedValue]))
                                                                                    <span
                                                                                        class="badge bg-success fs-6 px-3 py-2">
                                                                                        <i
                                                                                            class="fas fa-check-circle me-1"></i>
                                                                                        {{ $field->field_options[$selectedValue] }}
                                                                                    </span>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                @elseif (in_array($field->field_type, ['select', 'radio']) && $field->field_options)
                                                                    <span class="badge bg-info fs-6 px-3 py-2">
                                                                        <i class="fas fa-tag me-1"></i>
                                                                        {{ $field->field_options[$value] ?? $value }}
                                                                    </span>
                                                                @elseif ($field->field_type === 'textarea')
                                                                    <div class="bg-white p-3 rounded border"
                                                                        style="min-height: 80px;">
                                                                        <div class="text-dark">{!! nl2br(e($value)) !!}
                                                                        </div>
                                                                    </div>
                                                                @elseif ($field->field_type === 'date')
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-calendar-alt text-primary me-2"></i>
                                                                        <span class="text-dark fw-semibold">
                                                                            @try
                                                                                {{ \Carbon\Carbon::parse($value)->format('d F Y') }}
                                                                                @catch(\Exception $e)
                                                                                {{ $value }}
                                                                            @endtry
                                                                        </span>
                                                                    </div>
                                                                @elseif ($field->field_type === 'email')
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-envelope text-info me-2"></i>
                                                                        <a href="mailto:{{ $value }}"
                                                                            class="text-primary text-decoration-none">{{ $value }}</a>
                                                                    </div>
                                                                @elseif ($field->field_type === 'url')
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-link text-success me-2"></i>
                                                                        <a href="{{ $value }}" target="_blank"
                                                                            class="text-primary text-decoration-none">{{ $value }}</a>
                                                                    </div>
                                                                @elseif ($field->field_type === 'number')
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-hashtag text-secondary me-2"></i>
                                                                        <span
                                                                            class="text-dark fw-semibold">{{ number_format($value) }}</span>
                                                                    </div>
                                                                @else
                                                                    <div class="text-dark">{{ $value }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Modal Footer with Download Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Tutup
                        </button>

                        @if ($pengajuan->fileApproval && $pengajuan->fileApproval->file_surat)
                            <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}" target="_blank"
                                class="btn btn-info">
                                <i class="fas fa-eye me-1"></i>
                                Lihat Surat
                            </a>
                            <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}" download
                                class="btn btn-success">
                                <i class="fas fa-download me-1"></i>
                                Download Surat
                            </a>
                        @else
                            <button type="button" class="btn btn-warning" disabled>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                File Belum Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('styles')
    <style>
        /* Card Enhancement */
        .card-hover {
            transition: all 0.3s ease-in-out;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        /* Gradient Header */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .card-header {
            border-bottom: none;
            padding: 1rem;
        }

        .badge-status {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Enhanced Status Timeline */
        .status-step {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .status-step.pending {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            color: #6c757d;
        }

        .status-step.completed {
            background-color: #28a745;
            border: 2px solid #1e7e34;
            color: white;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .step-number {
            font-size: 11px;
            font-weight: 600;
        }

        .status-connector {
            height: 3px;
            background-color: #dee2e6;
            flex-grow: 1;
            margin: 0 8px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .status-connector.active {
            background-color: #28a745;
            box-shadow: 0 1px 3px rgba(40, 167, 69, 0.3);
        }

        .status-label {
            font-size: 0.7rem;
            font-weight: 500;
        }

        /* Progress Bar Enhancement */
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Keterangan Box */
        .keterangan-box {
            border-left: 4px solid #007bff;
            background-color: #f8f9fa !important;
            transition: all 0.2s ease;
        }

        .keterangan-box:hover {
            background-color: #e9ecef !important;
        }

        /* Card Footer */
        .card-footer {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
        }

        /* Button Enhancements */
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-outline-danger {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-outline-danger:hover {
            transform: translateY(-1px);
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-content {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Icons */
        .bi {
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .status-step {
                width: 24px;
                height: 24px;
                font-size: 10px;
            }

            .status-connector {
                margin: 0 4px;
            }

            .badge-status {
                font-size: 0.6rem;
                padding: 0.2rem 0.4rem;
            }

            .timeline {
                padding-left: 25px;
            }

            .timeline-marker {
                left: -20px;
                width: 25px;
                height: 25px;
            }
        }

        @media (max-width: 576px) {
            .card-hover:hover {
                transform: none;
            }

            .status-step {
                width: 20px;
                height: 20px;
                font-size: 9px;
            }

            .status-connector {
                margin: 0 2px;
            }

            .timeline {
                padding-left: 20px;
            }

            .timeline-marker {
                left: -17px;
                width: 20px;
                height: 20px;
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .status-step.pending {
            animation: pulse 2s infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const jenisFilter = document.getElementById('jenisFilter');
            const cardsContainer = document.getElementById('cardsContainer');
            const noResults = document.getElementById('noResults');

            function filterCards() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const jenisValue = jenisFilter.value.toLowerCase();

                const cards = document.querySelectorAll('.card-item');
                let visibleCount = 0;

                cards.forEach(card => {
                    const mahasiswa = card.getAttribute('data-mahasiswa');
                    const jenis = card.getAttribute('data-jenis');
                    const status = card.getAttribute('data-status');

                    let show = true;

                    // Search filter
                    if (searchTerm && !mahasiswa.includes(searchTerm) && !jenis.includes(searchTerm)) {
                        show = false;
                    }

                    // Status filter
                    if (statusValue && status !== statusValue) {
                        show = false;
                    }

                    // Jenis filter
                    if (jenisValue && !jenis.includes(jenisValue)) {
                        show = false;
                    }

                    if (show) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }

            // Event listeners for filters
            searchInput.addEventListener('input', filterCards);
            statusFilter.addEventListener('change', filterCards);
            jenisFilter.addEventListener('change', filterCards);

            // Reset filters function
            window.resetFilters = function() {
                searchInput.value = '';
                statusFilter.value = '';
                jenisFilter.value = '';
                filterCards();
            };

            // Modal keterangan lengkap
            const keteranganModal = document.getElementById('keteranganModal');
            keteranganModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const keterangan = button.getAttribute('data-keterangan');
                const mahasiswa = button.getAttribute('data-mahasiswa');

                document.getElementById('modalMahasiswaName').textContent = mahasiswa;
                document.getElementById('modalKeterangan').innerHTML = keterangan.replace(/\n/g, '<br>');
            });

            // Modal reject
            const rejectModal = document.getElementById('rejectModal');
            rejectModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const pengajuanId = button.getAttribute('data-id');
                const level = button.getAttribute('data-level');
                const form = document.getElementById('rejectForm');

                // Update action URL sesuai level dan id
                form.action = `/data/pengajuan/${pengajuanId}/reject_${level}`;
            });
        });
    </script>
@endpush
