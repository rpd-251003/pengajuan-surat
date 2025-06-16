@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">History Pengajuan Surat</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success') || session('error') || session('warning') || session('info'))
                            <div class="container mt-3">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Berhasil!</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <strong>Error!</strong> {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('warning'))
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <strong>Peringatan!</strong> {{ session('warning') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('info'))
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>Info!</strong> {{ session('info') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($pengajuanSurats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Surat</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Timeline Approval</th>
                                            <th>Download File Surat</th>
                                            <th>Tanggal Pengajuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengajuanSurats as $index => $pengajuan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $pengajuan->jenisSurat->nama ?? '-' }}</td>
                                                <td>{{ Str::limit($pengajuan->keterangan, 50) }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = '';
                                                        $statusText = '';
                                                        switch ($pengajuan->status) {
                                                            case 'diajukan':
                                                                $statusClass = 'bg-warning';
                                                                $statusText = 'Diajukan';
                                                                break;
                                                            case 'diproses':
                                                                $statusClass = 'bg-primary';
                                                                $statusText = 'Diproses';
                                                                break;
                                                            case 'disetujui':
                                                                $statusClass = 'bg-success';
                                                                $statusText = 'Disetujui';
                                                                break;
                                                            case 'ditolak':
                                                                $statusClass = 'bg-danger';
                                                                $statusText = 'Ditolak';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-secondary';
                                                                $statusText = 'Unknown';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td>
                                                    <!-- Timeline Approval -->
                                                    <div class="d-flex align-items-center">
                                                        <!-- Dosen PA -->
                                                        <div class="text-center me-3">
                                                            <div class="position-relative">
                                                                @if ($pengajuan->approved_at_dosen_pa)
                                                                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                        style="width: 12px; height: 12px;">
                                                                        <i class="fas fa-check text-white"
                                                                            style="font-size: 8px;"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-secondary rounded-circle"
                                                                        style="width: 12px; height: 12px;"></div>
                                                                @endif
                                                            </div>
                                                            <small class="d-block mt-1" style="font-size: 10px;">
                                                                Dosen PA<br>
                                                                @if ($pengajuan->approved_by_dosen_pa)
                                                                    <span
                                                                        class="text-muted">{{ $pengajuan->dosenPA->name ?? 'N/A' }}</span>
                                                                @else
                                                                    <span class="text-warning">Waiting..</span>
                                                                @endif
                                                            </small>
                                                        </div>

                                                        <!-- Arrow -->
                                                        <i class="fas fa-arrow-right text-muted me-3"
                                                            style="font-size: 10px;"></i>

                                                        <!-- Kaprodi -->
                                                        <div class="text-center me-3">
                                                            <div class="position-relative">
                                                                @if ($pengajuan->approved_at_kaprodi)
                                                                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                        style="width: 12px; height: 12px;">
                                                                        <i class="fas fa-check text-white"
                                                                            style="font-size: 8px;"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-secondary rounded-circle"
                                                                        style="width: 12px; height: 12px;"></div>
                                                                @endif
                                                            </div>
                                                            <small class="d-block mt-1" style="font-size: 10px;">
                                                                Kaprodi<br>
                                                                @if ($pengajuan->approved_by_kaprodi)
                                                                    <span
                                                                        class="text-muted">{{ $pengajuan->kaprodi->name ?? 'N/A' }}</span>
                                                                @else
                                                                    <span class="text-warning">Waiting..</span>
                                                                @endif
                                                            </small>
                                                        </div>

                                                        <!-- Arrow -->
                                                        <i class="fas fa-arrow-right text-muted me-3"
                                                            style="font-size: 10px;"></i>

                                                        <!-- Wadek1 -->
                                                        <div class="text-center me-3">
                                                            <div class="position-relative">
                                                                @if ($pengajuan->approved_at_wadek1)
                                                                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                        style="width: 12px; height: 12px;">
                                                                        <i class="fas fa-check text-white"
                                                                            style="font-size: 8px;"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-secondary rounded-circle"
                                                                        style="width: 12px; height: 12px;"></div>
                                                                @endif
                                                            </div>
                                                            <small class="d-block mt-1" style="font-size: 10px;">
                                                                Wadek1<br>
                                                                @if ($pengajuan->approved_by_wadek1)
                                                                    <span
                                                                        class="text-muted">{{ $pengajuan->wadek1->name ?? 'N/A' }}</span>
                                                                @else
                                                                    <span class="text-warning">Waiting..</span>
                                                                @endif
                                                            </small>
                                                        </div>

                                                        <!-- Arrow -->
                                                        <i class="fas fa-arrow-right text-muted me-3"
                                                            style="font-size: 10px;"></i>

                                                        <!-- Staff TU -->
                                                        <div class="text-center">
                                                            <div class="position-relative">
                                                                @if ($pengajuan->approved_at_staff_tu)
                                                                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                        style="width: 12px; height: 12px;">
                                                                        <i class="fas fa-check text-white"
                                                                            style="font-size: 8px;"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="bg-secondary rounded-circle"
                                                                        style="width: 12px; height: 12px;"></div>
                                                                @endif
                                                            </div>
                                                            <small class="d-block mt-1" style="font-size: 10px;">
                                                                TU (Rilis)<br>
                                                                @if ($pengajuan->approved_by_staff_tu)
                                                                    <span
                                                                        class="text-muted">{{ $pengajuan->staffTU->name ?? 'N/A' }}</span>
                                                                @else
                                                                    <span class="text-warning">Waiting..</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>

                                                    @if ($pengajuan->fileApproval)
                                                        <div class="mt-4">
                                                            <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                                target="_blank" class="btn btn-sm btn-success">
                                                                <i class="fas fa-download me-1"></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    @else
                                                    Belum Tersedia
                                                    @endif
                                                </td>
                                                <td>{{ $pengajuan->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada pengajuan surat</h5>
                                <p class="text-muted">Anda belum memiliki riwayat pengajuan surat.</p>
                                <a href="{{ route('pengajuan_surat.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Buat Pengajuan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .timeline-container {
                overflow-x: auto;
                white-space: nowrap;
            }

            .timeline-item {
                display: inline-block;
                vertical-align: top;
                text-align: center;
                margin-right: 20px;
            }

            .timeline-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 auto;
            }

            .timeline-dot.completed {
                background-color: #28a745;
            }

            .timeline-dot.pending {
                background-color: #6c757d;
            }

            .timeline-line {
                height: 2px;
                background-color: #dee2e6;
                margin: 5px 0;
            }

            @media (max-width: 768px) {
                .table-responsive {
                    font-size: 12px;
                }

                .timeline-container {
                    font-size: 9px;
                }
            }
        </style>
    @endpush
@endsection
