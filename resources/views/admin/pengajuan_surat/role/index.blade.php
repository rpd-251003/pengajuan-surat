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

        <div class="card card-body">
            <h3>Daftar Pengajuan Surat - Dosen</h3>
            <p class="text-muted">Kelola pengajuan surat mahasiswa sebagai Dosen PA/Kaprodi</p>

            <!-- Cards for displaying pengajuan -->
            <div class="row">
                @forelse ($pengajuanSurats as $pengajuan)
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
                                                <h6 class="mb-1 fw-bold">{{ $pengajuan->jenisSurat->nama ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $pengajuan->mahasiswa->user->name ?? 'N/A' }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    {{ $pengajuan->mahasiswa->nim ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status and timeline -->
                                    <div class="col-md-5">
                                        @php
                                            $statusClass = match($pengajuan->status) {
                                                'diajukan' => 'bg-warning',
                                                'diproses' => 'bg-primary',
                                                'disetujui' => 'bg-success',
                                                'ditolak' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp

                                        <div class="mb-2">
                                            <span class="badge {{ $statusClass }} fs-6">
                                                {{ ucfirst($pengajuan->status) }}
                                            </span>
                                        </div>

                                        <!-- Timeline -->
                                        <div class="d-flex align-items-center" style="font-size: 11px;">
                                            <!-- Dosen PA -->
                                            <div class="text-center me-2">
                                                <div class="position-relative">
                                                    @if ($pengajuan->approved_at_dosen_pa)
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
                                                    @if ($pengajuan->approved_at_kaprodi)
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
                                                    @if ($pengajuan->approved_at_wadek1)
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
                                                    @if ($pengajuan->approved_at_staff_tu)
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

                                        <!-- File Surat Info -->
                                        @if ($pengajuan->fileApproval)
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="fas fa-file-pdf me-1"></i>
                                                    File surat tersedia
                                                </small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            <!-- Detail Button -->
                                            @php
                                                $details = $pengajuan->getDetailsArray();
                                            @endphp
                                            @if (!empty($details) || $pengajuan->keterangan)
                                                <button type="button" class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal{{ $pengajuan->id }}">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                            @endif

                                            <!-- Download File Surat -->
                                            @if ($pengajuan->fileApproval)
                                                <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                   target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            @endif

                                            <!-- Role-based Actions -->
                                            @if ($pengajuan->status !== 'ditolak')
                                                @php
                                                    $canApproveAsDosenPA = Auth::user()->canApproveAsDosenPA($pengajuan->tahun_angkatan, $pengajuan->prodi_id);
                                                    $canApproveAsKaprodi = Auth::user()->canApproveAsKaprodi($pengajuan->tahun_angkatan, $pengajuan->prodi_id);
                                                    $canApproveDouble = $canApproveAsDosenPA && $canApproveAsKaprodi;
                                                @endphp

                                                @if ($canApproveDouble && !$pengajuan->approved_at_dosen_pa && !$pengajuan->approved_at_kaprodi)
                                                    <!-- Approve Double (PA & Kaprodi) -->
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        onclick="confirmApprove('{{ route('admin.pengajuan.approve_double', $pengajuan->id) }}')">
                                                        <i class="fas fa-check-double me-1"></i> Approve PA & Kaprodi
                                                    </button>

                                                    <!-- Reject Double -->
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="showRejectModal('{{ route('admin.pengajuan.reject_double', $pengajuan->id) }}')">
                                                        <i class="fas fa-times me-1"></i> Tolak
                                                    </button>
                                                @else
                                                    <!-- Individual Approvals -->
                                                    @if ($canApproveAsDosenPA && !$pengajuan->approved_at_dosen_pa)
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_dosen_pa', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve PA
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_dosen_pa', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak PA
                                                        </button>
                                                    @endif

                                                    @if ($canApproveAsKaprodi && !$pengajuan->approved_at_kaprodi)
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_kaprodi', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve Kaprodi
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_kaprodi', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak Kaprodi
                                                        </button>
                                                    @endif

                                                    @if (Auth::user()->hasRole('wadek1') && !$pengajuan->approved_at_wadek1 && $pengajuan->approved_at_dosen_pa && $pengajuan->approved_at_kaprodi)
                                                        <button type="button" class="btn btn-sm btn-info"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_wadek1', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve Wadek1
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_wadek1', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak Wadek1
                                                        </button>
                                                    @endif

                                                    @if (Auth::user()->hasRole('tu') && !$pengajuan->approved_at_staff_tu && $pengajuan->approved_at_wadek1)
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_staff_tu', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve TU
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_staff_tu', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak TU
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($pengajuan->approved_at_dosen_pa && $pengajuan->approved_at_kaprodi && $pengajuan->approved_at_wadek1 && $pengajuan->approved_at_staff_tu)
                                                    <span class="badge bg-success">Pengajuan Selesai</span>
                                                @elseif (!$canApproveAsDosenPA && !$canApproveAsKaprodi && !Auth::user()->hasRole(['wadek1', 'tu']))
                                                    <span class="text-muted small">Tidak ada aksi tersedia</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Pengajuan Ditolak</span>
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
        </div>dek1', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve Wadek1
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_wadek1', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak Wadek1
                                                        </button>
                                                    @endif

                                                    @if (Auth::user()->hasRole('tu') && !$pengajuan->approved_at_staff_tu && $pengajuan->approved_at_wadek1)
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="confirmApprove('{{ route('admin.pengajuan.approve_staff_tu', $pengajuan->id) }}')">
                                                            <i class="fas fa-check me-1"></i> Approve TU
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="showRejectModal('{{ route('admin.pengajuan.reject_staff_tu', $pengajuan->id) }}')">
                                                            <i class="fas fa-times me-1"></i> Tolak TU
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($pengajuan->approved_at_dosen_pa && $pengajuan->approved_at_kaprodi && $pengajuan->approved_at_wadek1 && $pengajuan->approved_at_staff_tu)
                                                    <span class="badge bg-success">Pengajuan Selesai</span>
                                                @elseif (!$canApproveAsDosenPA && !$canApproveAsKaprodi && !Auth::user()->hasRole(['wadek1', 'tu']))
                                                    <span class="text-muted small">Tidak ada aksi tersedia</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Pengajuan Ditolak</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                        class="bi bi-inbox mb-2" viewBox="0 0 16 16">
                        <path
                            d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438L14.933 9zM3.809 3.563A1.5 1.5 0 0 1 4.981 3h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                    </svg>
                    <h5>Tidak ada pengajuan surat</h5>
                    <p class="mb-0">Belum ada pengajuan surat yang sesuai dengan filter yang dipilih.</p>
                </div>
            @endif
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
                                                                        <i class="fas fa-file me-2 text-primary"></i>
                                                                        <div>
                                                                            <a href="{{ asset('storage/' . $fileInfo['path']) }}"
                                                                               target="_blank" class="text-decoration-none">
                                                                                {{ $fileInfo['original_name'] ?? 'File' }}
                                                                            </a>
                                                                            @if (isset($fileInfo['size']))
                                                                                <small class="text-muted d-block">
                                                                                    ({{ number_format($fileInfo['size'] / 1024, 2) }} KB)
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">File tidak tersedia</span>
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

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pengajuan Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
@endsection

@push('scripts')
    <script>
        function confirmApprove(url) {
            document.getElementById('approveForm').action = url;
            new bootstrap.Modal(document.getElementById('approveModal')).show();
        }

        function showRejectModal(url) {
            document.getElementById('rejectForm').action = url;
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 5000);
            });
        });
    </script>
@endpush
