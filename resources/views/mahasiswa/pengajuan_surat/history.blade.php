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
                                            <th>Detail Pengajuan</th>
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

                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $details = $pengajuan->getDetailsArray();
                                                    @endphp
                                                    @if (!empty($details) || $pengajuan->keterangan)
                                                        <button type="button" class="btn btn-sm btn-info"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#detailModal{{ $pengajuan->id }}">
                                                            <i class="fas fa-eye me-1"></i> Lihat Detail
                                                        </button>
                                                    @else
                                                        <span class="text-muted">Tidak ada detail</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($pengajuan->fileApproval)
                                                        <div>
                                                            <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                                                                target="_blank" class="btn btn-sm btn-success">
                                                                <i class="fas fa-download me-1"></i>
                                                                Download File
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Belum Tersedia</span>
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

    <!-- Detail Modals -->
    @foreach ($pengajuanSurats as $pengajuan)
        <div class="modal fade" id="detailModal{{ $pengajuan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-alt me-2"></i>
                            Detail Pengajuan: {{ $pengajuan->jenisSurat->nama ?? 'N/A' }}
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
                                        <h6 class="mb-2"><i class="fas fa-users me-1"></i> Timeline Approval</h6>
                                        <!-- Dosen PA -->
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($pengajuan->approved_at_dosen_pa)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @endif
                                            <div>
                                                <strong>Dosen PA:</strong> {{ $pengajuan->dosenPA->name ?? 'Belum ditentukan' }}<br>
                                                <small class="text-muted">
                                                    @if ($pengajuan->approved_at_dosen_pa)
                                                        Disetujui: {{ \Carbon\Carbon::parse($pengajuan->approved_at_dosen_pa)->format('d/m/Y H:i') }}
                                                    @else
                                                        Menunggu persetujuan
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <!-- Kaprodi -->
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($pengajuan->approved_at_kaprodi)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @endif
                                            <div>
                                                <strong>Kaprodi:</strong> {{ $pengajuan->kaprodi->name ?? 'Belum ditentukan' }}<br>
                                                <small class="text-muted">
                                                    @if ($pengajuan->approved_at_kaprodi)
                                                        Disetujui: {{ \Carbon\Carbon::parse($pengajuan->approved_at_kaprodi)->format('d/m/Y H:i') }}
                                                    @else
                                                        Menunggu persetujuan
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <!-- Wadek1 -->
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($pengajuan->approved_at_wadek1)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @endif
                                            <div>
                                                <strong>Wadek 1:</strong> {{ $pengajuan->wadek1->name ?? 'Belum ditentukan' }}<br>
                                                <small class="text-muted">
                                                    @if ($pengajuan->approved_at_wadek1)
                                                        Disetujui: {{ \Carbon\Carbon::parse($pengajuan->approved_at_wadek1)->format('d/m/Y H:i') }}
                                                    @else
                                                        Menunggu persetujuan
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <!-- Staff TU -->
                                        <div class="d-flex align-items-center">
                                            @if ($pengajuan->approved_at_staff_tu)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @endif
                                            <div>
                                                <strong>Staff TU:</strong> {{ $pengajuan->staffTU->name ?? 'Belum ditentukan' }}<br>
                                                <small class="text-muted">
                                                    @if ($pengajuan->approved_at_staff_tu)
                                                        Selesai: {{ \Carbon\Carbon::parse($pengajuan->approved_at_staff_tu)->format('d/m/Y H:i') }}
                                                    @else
                                                        Menunggu pemrosesan
                                                    @endif
                                                </small>
                                            </div>
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
                                @foreach ($pengajuan->jenisSurat->fields as $field)
                                    @php
                                        $value = $details[$field->field_name] ?? '';
                                    @endphp
                                    @if ($value)
                                        <div class="mb-3">
                                            <strong>{{ $field->field_label }}:</strong>
                                            <div class="mt-1">
                                                @if ($field->field_type === 'file')
                                                    @php
                                                        $fileInfo = is_array($value) ? $value : json_decode($value, true);
                                                    @endphp
                                                    @if ($fileInfo && isset($fileInfo['original_name']))
                                                        <div class="d-flex align-items-center p-2 border rounded bg-light">
                                                            <div class="me-2">
                                                                @php
                                                                    $ext = strtolower(pathinfo($fileInfo['original_name'], PATHINFO_EXTENSION));
                                                                    $icon = match($ext) {
                                                                        'pdf' => 'fas fa-file-pdf text-danger',
                                                                        'doc', 'docx' => 'fas fa-file-word text-primary',
                                                                        'jpg', 'jpeg', 'png' => 'fas fa-file-image text-info',
                                                                        default => 'fas fa-file text-secondary'
                                                                    };
                                                                @endphp
                                                                <i class="{{ $icon }}" style="font-size: 1.5rem;"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold">{{ $fileInfo['original_name'] }}</div>
                                                                <small class="text-muted">
                                                                    {{ number_format($fileInfo['size'] / 1024, 2) }} KB
                                                                    • {{ \Carbon\Carbon::parse($fileInfo['uploaded_at'])->format('d/m/Y H:i') }}
                                                                </small>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('pengajuan.file.view', [$pengajuan->id, $field->field_name]) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-primary me-1"
                                                                   title="Buka file">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('pengajuan.file.download', [$pengajuan->id, $field->field_name]) }}"
                                                                   class="btn btn-sm btn-outline-success"
                                                                   title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($field->field_type === 'checkbox')
                                                    @php
                                                        $selectedValues = is_string($value) ? json_decode($value, true) : $value;
                                                        $selectedValues = is_array($selectedValues) ? $selectedValues : [$value];
                                                    @endphp
                                                    @if ($field->field_options)
                                                        <ul class="list-unstyled">
                                                            @foreach ($selectedValues as $selectedValue)
                                                                @if (isset($field->field_options[$selectedValue]))
                                                                    <li>
                                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                                        {{ $field->field_options[$selectedValue] }}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @elseif (in_array($field->field_type, ['select', 'radio']) && $field->field_options)
                                                    <span class="badge bg-info">{{ $field->field_options[$value] ?? $value }}</span>
                                                @elseif ($field->field_type === 'textarea')
                                                    <div class="bg-light p-2 rounded border">
                                                        {!! nl2br(e($value)) !!}
                                                    </div>
                                                @else
                                                    <span class="text-dark">{{ $value }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Tutup
                        </button>
                        @if ($pengajuan->fileApproval)
                            <a href="{{ asset('storage/' . $pengajuan->fileApproval->file_surat) }}"
                               target="_blank"
                               class="btn btn-success">
                                <i class="fas fa-download me-1"></i>Download Surat Final
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

            /* File attachment styling */
            .file-attachment {
                transition: all 0.3s ease;
            }

            .file-attachment:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            /* Modal responsive */
            @media (max-width: 768px) {
                .table-responsive {
                    font-size: 12px;
                }

                .timeline-container {
                    font-size: 9px;
                }

                .modal-lg {
                    max-width: 95%;
                }

                .file-attachment .d-flex {
                    flex-direction: column;
                    align-items: flex-start !important;
                }

                .file-attachment .btn {
                    margin-top: 0.5rem;
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
