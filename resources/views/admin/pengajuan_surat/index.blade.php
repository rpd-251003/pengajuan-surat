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
            <!-- Search & Filter Section -->
            <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="mb-4 mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Cari mahasiswa, jenis surat...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="jenis_surat">
                            <option value="">Semua Jenis Surat</option>
                            @foreach ($jenisSuratOptions as $jenis)
                                <option value="{{ $jenis }}"
                                    {{ request('jenis_surat') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan
                            </option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-funnel" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                            </svg>
                        </button>
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                <path
                                    d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Cards Grid -->
            <div class="row">
                @forelse($pengajuanSurats as $p)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <strong>{{ $p->mahasiswa->user->name ?? 'N/A' }}</strong>
                                </h6>
                                <small class="text-muted d-block">{{ $p->jenisSurat->nama ?? 'N/A' }}</small>

                                {{-- Status Badge --}}
                                @php
                                    $status = $p->status ?? 'unknown';
                                    $badgeClass = match ($status) {
                                        'diajukan' => 'bg-warning text-dark',
                                        'diproses' => 'bg-primary text-white',
                                        'disetujui' => 'bg-success text-white',
                                        'ditolak' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-5">{{ ucfirst($status) }}</span>
                            </div>

                            <div class="card-body">
                                <!-- Keterangan -->
                                <div class="mb-3">
                                    <label class="fw-bold small">Keterangan:</label>
                                    <div class="keterangan-content">
                                        <span class="keterangan-short">
                                            {{ Str::limit(strip_tags($p->keterangan), 100) }}
                                        </span>
                                        @if (strlen(strip_tags($p->keterangan)) > 100)
                                            <span class="keterangan-full d-none">
                                                {!! nl2br(e($p->keterangan)) !!}
                                            </span>
                                            <a href="javascript:void(0)"
                                                class="text-primary small toggle-keterangan">Lihat selengkapnya</a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Info Approver -->
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Dosen PA:</small><br>
                                            <small class="fw-bold">{{ $p->dosenPA->name ?? 'Belum ditentukan' }}</small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Kaprodi:</small><br>
                                            <small class="fw-bold">{{ $p->kaprodi->name ?? 'Belum ditentukan' }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Status -->
                                <div class="timeline-status">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $isDosenPAApproved = $p->approved_by_dosen_pa && $p->approved_at_dosen_pa;
                                        @endphp
                                        <div class="timeline-icon me-2">
                                            @if ($isDosenPAApproved)
                                                <span class="badge bg-success rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <small
                                            class="timeline-text {{ $isDosenPAApproved ? 'text-success' : 'text-muted' }}">
                                            Dosen PA {{ $isDosenPAApproved ? 'Approved' : 'Pending' }}
                                        </small>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $isKaprodiApproved = $p->approved_by_kaprodi && $p->approved_at_kaprodi;
                                        @endphp
                                        <div class="timeline-icon me-2">
                                            @if ($isKaprodiApproved)
                                                <span class="badge bg-success rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <small
                                            class="timeline-text {{ $isKaprodiApproved ? 'text-success' : 'text-muted' }}">
                                            Kaprodi {{ $isKaprodiApproved ? 'Approved' : 'Pending' }}
                                        </small>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $isWadek1Approved = $p->approved_by_wadek1 && $p->approved_at_wadek1;
                                        @endphp
                                        <div class="timeline-icon me-2">
                                            @if ($isWadek1Approved)
                                                <span class="badge bg-success rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <small
                                            class="timeline-text {{ $isWadek1Approved ? 'text-success' : 'text-muted' }}">
                                            Wadek 1 {{ $isWadek1Approved ? 'Approved' : 'Pending' }}
                                        </small>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        @php
                                            $isStaffTUApproved = $p->approved_by_staff_tu && $p->approved_at_staff_tu;
                                        @endphp
                                        <div class="timeline-icon me-2">
                                            @if ($isStaffTUApproved)
                                                <span class="badge bg-success rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-circle p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <small
                                            class="timeline-text {{ $isStaffTUApproved ? 'text-success' : 'text-muted' }}">
                                            Staff TU {{ $isStaffTUApproved ? 'Approved' : 'Pending' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                @php $role = Auth::user()->role; @endphp
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

                                @if ($role == 'wadek1')
                                    @if (is_null($p->approved_by_wadek1))
                                        <form method="POST"
                                            action="{{ route('admin.pengajuan.approve_wadek1', $p->id) }}"
                                            class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success me-1"
                                                onclick="return confirm('Yakin approve?')">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-danger btn-reject"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal"
                                        data-id="{{ $p->id }}" data-level="wadek1">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                @elseif($role == 'tu')
                                    {{-- Tombol Approve --}}
                                    <form method="POST" action="{{ route('admin.pengajuan.approve_staff_tu', $p->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success me-1"
                                            onclick="return confirm('Yakin approve?')">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>

                                    {{-- Tombol Reject --}}
                                    <button type="button" class="btn btn-sm btn-danger btn-reject"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal"
                                        data-id="{{ $p->id }}" data-level="staff_tu">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>

                                    {{-- Tombol Upload Surat --}}
                                    @if (!$p->fileApproval)
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#uploadModal{{ $p->id }}">
                                            <i class="fas fa-upload me-1"></i> Upload Surat
                                        </button>


                                        <div class="modal fade" id="uploadModal{{ $p->id }}" tabindex="-1"
                                            aria-labelledby="uploadModalLabel{{ $p->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('file-approvals.store') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id_pengajuan"
                                                        value="{{ $p->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="uploadModalLabel{{ $p->id }}">
                                                                Upload Surat
                                                                untuk <br> {{ $p->mahasiswa->user->name ?? 'N/A' }}<small
                                                                    class="text-muted d-block">{{ $p->jenisSurat->nama ?? 'N/A' }}</small>
                                                            </h5>

                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="nomor_surat" class="form-label">
                                                                    <i class="fas fa-file-alt me-1 text-primary"></i> Nomor
                                                                    Surat
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    name="nomor_surat" id="nomor_surat" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="file_surat" class="form-label">
                                                                    <i class="fas fa-upload me-1 text-success"></i> Upload
                                                                    Surat
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="file_surat" id="file_surat"
                                                                    accept=".pdf,.doc,.docx" required>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
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
                                    @else
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewModal{{ $p->id }}">
                                            <i class="fas fa-eye me-1"></i> Lihat Surat
                                        </button>

                                        <div class="modal fade" id="viewModal{{ $p->id }}" tabindex="-1"
                                            aria-labelledby="viewModalLabel{{ $p->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel{{ $p->id }}">
                                                            Surat
                                                            - Pengajuan
                                                            #{{ $p->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{-- Tabs --}}
                                                        <ul class="nav nav-tabs mb-3" id="tabSurat{{ $p->id }}"
                                                            role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link active"
                                                                    id="detail-tab{{ $p->id }}"
                                                                    data-bs-toggle="tab"
                                                                    data-bs-target="#detail{{ $p->id }}"
                                                                    type="button" role="tab">
                                                                    Detail
                                                                </button>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="edit-tab{{ $p->id }}"
                                                                    data-bs-toggle="tab"
                                                                    data-bs-target="#edit{{ $p->id }}"
                                                                    type="button" role="tab">
                                                                    Edit
                                                                </button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content" id="tabContentSurat{{ $p->id }}">
                                                            {{-- Detail Tab --}}
                                                            <div class="tab-pane fade show active"
                                                                id="detail{{ $p->id }}" role="tabpanel">
                                                                <div class="card border-0 shadow-sm">
                                                                    <div class="card-body">
                                                                        <h6 class="text-primary mb-3"><i
                                                                                class="fas fa-file-alt me-1"></i> Detail
                                                                            Surat</h6>

                                                                        <div class="mb-2">
                                                                            <i class="fas fa-hashtag text-muted me-2"></i>
                                                                            <strong>Nomor Surat:</strong>
                                                                            <span
                                                                                class="text-dark">{{ $p->fileApproval->nomor_surat }}</span>
                                                                        </div>

                                                                        <div class="mb-2">
                                                                            <i class="fas fa-file-pdf text-muted me-2"></i>
                                                                            <strong>Nama File:</strong>
                                                                            <span
                                                                                class="text-dark">{{ basename($p->fileApproval->file_surat) }}</span>
                                                                        </div>

                                                                        <div class="mt-4 text-end">
                                                                            <a href="{{ asset('storage/' . $p->fileApproval->file_surat) }}"
                                                                                target="_blank"
                                                                                class="btn btn-sm btn-success">
                                                                                <i class="fas fa-download me-1"></i>
                                                                                Download File
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            {{-- Edit Tab --}}
                                                            <div class="tab-pane fade" id="edit{{ $p->id }}"
                                                                role="tabpanel">
                                                                <form
                                                                    action="{{ route('file-approvals.update', $p->fileApproval->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="id_pengajuan"
                                                                        value="{{ $p->id }}">

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Nomor Surat</label>
                                                                        <input type="text" name="nomor_surat"
                                                                            value="{{ $p->fileApproval->nomor_surat }}"
                                                                            class="form-control" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Ganti File Surat
                                                                            (opsional)
                                                                        </label>
                                                                        <input type="file" name="file_surat"
                                                                            class="form-control" accept=".pdf,.doc,.docx">
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-save me-1"></i> Simpan
                                                                            Perubahan
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div> {{-- end modal-body --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Upload Surat -->
                                    @endif



                                    {{-- Modal Upload Surat --}}
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif

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
                                            <strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? 'N/A' }}
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
                                                                                    @try
                                                                                        {{ $fileInfo['uploaded_at'] }}
                                                                                        @catch(\Exception $e)
                                                                                        {{ $fileInfo['uploaded_at'] ?? 'N/A' }}
                                                                                    @endtry
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    }
                }, 5000); // 5 seconds
            });

            // Toggle keterangan
            document.querySelectorAll('.toggle-keterangan').forEach(button => {
                button.addEventListener('click', function() {
                    const parent = this.closest('.keterangan-content');
                    const shortText = parent.querySelector('.keterangan-short');
                    const fullText = parent.querySelector('.keterangan-full');

                    if (shortText.classList.contains('d-none')) {
                        // Show short, hide full
                        shortText.classList.remove('d-none');
                        fullText.classList.add('d-none');
                        this.textContent = 'Lihat selengkapnya';
                    } else {
                        // Show full, hide short
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'Lihat lebih sedikit';
                    }
                });
            });

            // Modal reject
            let rejectModal = document.getElementById('rejectModal');
            rejectModal.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget;
                let pengajuanId = button.getAttribute('data-id');
                let level = button.getAttribute('data-level');
                let form = document.getElementById('rejectForm');

                // Update action URL sesuai level dan id
                form.action = `/admin/pengajuan/${pengajuanId}/reject_${level}`;
            });
        });
    </script>
@endpush
