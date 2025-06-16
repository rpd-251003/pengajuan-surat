@extends('layouts.default')

@section('content')
    <div class="container mt-3">
        <h1 class="mb-1">Selamat Datang</h1>
        <h3 class="mb-1 fw-light">{{ Auth::user()->name }}</h3>
        <div class="row mt-5">

            <!-- Recent Letter Card -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-clock-history me-2"></i>
                            Pengajuan Surat Terbaru
                        </h5>


                        <a href="{{ route('pengajuan_surat.history') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-eye me-1"></i>
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-warning fs-6 mb-1 me-1">Diajukan: {{ $pengajuanCounts['diajukan'] }}</span>
                            <span class="badge bg-primary fs-6 mb-1 text-white me-1">Diproses:
                                {{ $pengajuanCounts['diproses'] }}</span>
                            <span class="badge bg-success fs-6 mb-1 me-1">Disetujui: {{ $pengajuanCounts['disetujui'] }}</span>
                            <span class="badge bg-danger fs-6 mb-1">Ditolak: {{ $pengajuanCounts['ditolak'] }}</span>
                        </div>
                        <hr>
                        @if (isset($latestPengajuan) && $latestPengajuan)
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @php
                                                $statusClass = '';
                                                $statusIcon = '';
                                                switch ($latestPengajuan->status) {
                                                    case 'diajukan':
                                                        $statusClass = 'text-warning';
                                                        $statusIcon = 'ti-clock';
                                                        break;
                                                    case 'diproses':
                                                        $statusClass = 'text-primary';
                                                        $statusIcon = 'ti-refresh';
                                                        break;
                                                    case 'disetujui':
                                                        $statusClass = 'text-success';
                                                        $statusIcon = 'ti-check';
                                                        break;
                                                    case 'ditolak':
                                                        $statusClass = 'text-danger';
                                                        $statusIcon = 'ti-x';
                                                        break;
                                                    default:
                                                        $statusClass = 'text-secondary';
                                                        $statusIcon = 'ti-help';
                                                }
                                            @endphp
                                            <i class="ti {{ $statusIcon }} {{ $statusClass }}"
                                                style="font-size: 2rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $latestPengajuan->jenisSurat->nama ?? 'Jenis Surat' }}
                                            </h6>

                                            <small
                                                class="text-muted">{{ $latestPengajuan->created_at->format('d/m/Y H:i') }}</small>
                                            @if ($latestPengajuan->fileApproval)
                                                <div class="mt-1 mb-2">
                                                    <a href="{{ asset('storage/' . $latestPengajuan->fileApproval->file_surat) }}"
                                                        target="_blank" class="btn btn-sm btn-success">
                                                        <i class="fas fa-download me-1"></i>
                                                        Download File
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @php
                                        $badgeClass = '';
                                        $statusText = '';
                                        switch ($latestPengajuan->status) {
                                            case 'diajukan':
                                                $badgeClass = 'bg-warning';
                                                $statusText = 'Diajukan';
                                                break;
                                            case 'diproses':
                                                $badgeClass = 'bg-primary';
                                                $statusText = 'Diproses';
                                                break;
                                            case 'disetujui':
                                                $badgeClass = 'bg-success';
                                                $statusText = 'Disetujui';
                                                break;
                                            case 'ditolak':
                                                $badgeClass = 'bg-danger';
                                                $statusText = 'Ditolak';
                                                break;
                                            default:
                                                $badgeClass = 'bg-secondary';
                                                $statusText = 'Unknown';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} fs-6">{{ $statusText }}</span>
                                    <div class="mt-1">
                                        <small
                                            class="text-muted">{{ $latestPengajuan->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Progress Timeline -->
                                    <div class="d-flex align-items-center justify-content-center" style="gap: 15px;">
                                        <!-- Dosen PA -->
                                        <div class="text-center">
                                            @if ($latestPengajuan->approved_at_dosen_pa)
                                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Dosen PA - Approved">
                                                    <i class="ti ti-check text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @else
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Dosen PA - Pending">
                                                    <i class="ti ti-clock text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                <small style="font-size: 9px;">Dosen PA</small>
                                            </div>
                                        </div>

                                        <i class="ti ti-arrow-right text-muted"></i>

                                        <!-- Kaprodi -->
                                        <div class="text-center">
                                            @if ($latestPengajuan->approved_at_kaprodi)
                                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Kaprodi - Approved">
                                                    <i class="ti ti-check text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @else
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Kaprodi - Pending">
                                                    <i class="ti ti-clock text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                <small style="font-size: 9px;">Kaprodi</small>
                                            </div>
                                        </div>

                                        <i class="ti ti-arrow-right text-muted"></i>

                                        <!-- Wadek1 -->
                                        <div class="text-center">
                                            @if ($latestPengajuan->approved_at_wadek1)
                                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Wadek1 - Approved">
                                                    <i class="ti ti-check text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @else
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Wadek1 - Pending">
                                                    <i class="ti ti-clock text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                <small style="font-size: 9px;">Wadek1</small>
                                            </div>
                                        </div>

                                        <i class="ti ti-arrow-right text-muted"></i>

                                        <!-- Staff TU -->
                                        <div class="text-center">
                                            @if ($latestPengajuan->approved_at_staff_tu)
                                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Staff TU - Completed">
                                                    <i class="ti ti-check text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @else
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 20px; height: 20px;" title="Staff TU - Pending">
                                                    <i class="ti ti-clock text-white" style="font-size: 10px;"></i>
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                <small style="font-size: 9px;">TU</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            @if ($latestPengajuan->keterangan)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="bg-light p-2 rounded">
                                            <small class="text-muted d-block">Keterangan:</small>
                                            <small>{{ Str::limit($latestPengajuan->keterangan, 1000) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3 mb-2">Belum ada pengajuan surat</h6>
                                <p class="text-muted small mb-3">Buat pengajuan surat pertama Anda sekarang</p>
                                <a href="{{ route('pengajuan_surat.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i>
                                    Buat Pengajuan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ti ti-mail-forward" style="font-size: 3rem; color: #4680ff;"></i>
                        </div>
                        <h5 class="card-title">Pengajuan Surat</h5>
                        <p class="card-text">Buat pengajuan surat baru dengan mudah dan cepat</p>
                        <a href="{{ route('pengajuan_surat.create') }}" class="btn btn-primary btn-lg">
                            <i class="ti ti-mail-forward me-2"></i>
                            Buat Surat
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="ti ti-history" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <h5 class="card-title">History Pengajuan</h5>
                        <p class="card-text">Lihat riwayat dan status pengajuan surat Anda</p>
                        <a href="{{ route('pengajuan_surat.history') }}" class="btn btn-success btn-lg">
                            <i class="ti ti-history me-2"></i>
                            Lihat History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
