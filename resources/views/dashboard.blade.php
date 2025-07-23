@extends('layouts.default')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard Pengajuan Surat</h1>
            {{-- <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshStats()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div> --}}
        </div>

        <!-- Stats Cards Row -->
        <div class="row mb-2">
            <div class="col-xl-6 col-md-6 mb-2">
                <div class="card border-start-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Users</div>
                                <small class="text-muted">Semua pengguna terdaftar di sistem</small>
                                <div class="h5 mb-0 fw-bold text-dark" id="total-users">{{ number_format($totalUsers) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-success h-100 py-2">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Mahasiswa</div>
                                <small class="text-muted">Pengguna dengan peran mahasiswa</small>
                                <div class="h5 mb-0 fw-bold text-dark" id="total-mahasiswa">
                                    {{ number_format($totalMahasiswa) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-xl-6 col-md-6 mb-2">
                <div class="card border-start-info h-100 py-2">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Surat</div>
                                <small class="text-muted">Jumlah seluruh pengajuan surat</small>
                                <div class="h5 mb-0 fw-bold text-dark" id="total-surat">{{ number_format($totalSurat) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-alt fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Progress Approval</div>
                                <small class="text-muted">Persentase surat yang disetujui penuh</small>
                                <div class="h5 mb-0 fw-bold text-dark">{{ $approvalProgress }}%</div>
                                <div class="progress progress-sm me-2">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $approvalProgress }}%" aria-valuenow="{{ $approvalProgress }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

        <!-- Chart dan Recent Surat Row -->
        <div class="row mb-2">
            <div class="col-lg-8">
                <div class="card mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Grafik Semua Surat (30 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="suratChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
            @if (Auth::user()->role == 'tu')
            <div class="col-lg-4">
                <div class="card mb-2" style="height: 300pt; max-height: 300pt">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Surat Terbaru</h6>
                    </div>
                    <div class="card-body" style="overflow-y: auto;">
                        @if ($recentSurat->count() > 0)
                            @foreach ($recentSurat as $surat)
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="fw-bold text-sm">{{ $surat['mahasiswa_name'] }}</div>
                                    <div class="text-xs text-muted">{{ $surat['jenis_surat'] }}</div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span
                                            class="badge fs-6 bg-{{ $surat['status'] == 'disetujui' ? 'success' : ($surat['status'] == 'ditolak' ? 'danger' : ($surat['status'] == 'diproses' ? 'info' : 'warning')) }}">
                                            {{ ucfirst($surat['status']) }}
                                        </span>
                                        <small class="text-muted">{{ $surat['created_at'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">Belum ada surat terbaru</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Surat Row -->
        <div class="row mb-2">
            <div class="col-lg-8">
                <div class="card mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Status Surat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="text-lg fw-bold text-warning">
                                        {{ number_format($statusCounts['diajukan']) }}
                                    </div>
                                    <div class="text-sm text-muted">Diajukan</div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="text-lg fw-bold text-info">{{ number_format($statusCounts['diproses']) }}
                                    </div>
                                    <div class="text-sm text-muted">Diproses</div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="text-lg fw-bold text-success">
                                        {{ number_format($statusCounts['disetujui']) }}</div>
                                    <div class="text-sm text-muted">Disetujui</div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="text-lg fw-bold text-danger">{{ number_format($statusCounts['ditolak']) }}
                                    </div>
                                    <div class="text-sm text-muted">Ditolak</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Pending Approvals</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Dosen PA</span>
                                <span class="badge fs-6 bg-warning text-dark">{{ $pendingApprovals['dosen_pa'] }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Kaprodi</span>
                                <span class="badge fs-6 bg-warning text-dark">{{ $pendingApprovals['kaprodi'] }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Wadek 1</span>
                                <span class="badge fs-6 bg-warning text-dark">{{ $pendingApprovals['wadek1'] }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Staff TU</span>
                                <span class="badge fs-6 bg-warning text-dark">{{ $pendingApprovals['staff_tu'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




            <!-- Penanggung Jawab Row -->
            <div class="row mb-2">
                <div class="col-lg-6">
                    <div class="card mb-2">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 fw-bold text-primary">Pending Dosen PA</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="showPendingDetails('dosen_pa')">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </div>
                        <div class="card-body">
                            @if ($dosenPAList->count() > 0)
                                @foreach ($dosenPAList as $dosen)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <div class="fw-bold">{{ $dosen['name'] }}</div>
                                        </div>
                                        <span class="badge fs-6 bg-danger">{{ $dosen['pending_count'] }} pending</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">Tidak ada pending approval</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-2">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 fw-bold text-primary">Pending Kaprodi</h6>
                            <button class="btn btn-sm btn-outline-primary" onclick="showPendingDetails('kaprodi')">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </div>
                        <div class="card-body">
                            @if ($kaprodiList->count() > 0)
                                @foreach ($kaprodiList as $kaprodi)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <div class="fw-bold">{{ $kaprodi['name'] }}</div>
                                        </div>
                                        <span class="badge fs-6 bg-danger">{{ $kaprodi['pending_count'] }} pending</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">Tidak ada pending approval</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Modal untuk Detail Pending -->
    <div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pendingModalLabel">Detail Pending Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pendingContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="myToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Data berhasil di-refresh!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .border-start-primary {
            border-left: 0.25rem solid #0d6efd !important;
        }

        .border-start-success {
            border-left: 0.25rem solid #198754 !important;
        }

        .border-start-info {
            border-left: 0.25rem solid #0dcaf0 !important;
        }

        .border-start-warning {
            border-left: 0.25rem solid #ffc107 !important;
        }

        .text-lg {
            font-size: 1.25rem;
        }

        .progress-sm {
            height: 0.5rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Configuration
        const chartData = @json($chartData);
        const ctx = document.getElementById('suratChart').getContext('2d');
        const suratChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.formatted_date),
                datasets: [{
                    label: 'Jumlah Surat',
                    data: chartData.map(item => item.count),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Functions
        function refreshStats() {
            fetch('/dashboard/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-users').textContent = new Intl.NumberFormat().format(data
                        .total_users);
                    document.getElementById('total-mahasiswa').textContent = new Intl.NumberFormat().format(data
                        .total_mahasiswa);
                    document.getElementById('total-surat').textContent = new Intl.NumberFormat().format(data
                        .total_surat);

                    // Update status counts if needed
                    // You can add more DOM updates here

                    // Show success message
                    const toastEl = document.getElementById('myToast');
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal refresh data!');
                });
        }

        function showPendingDetails(type) {
            const modal = new bootstrap.Modal(document.getElementById('pendingModal'));
            const title = type === 'dosen_pa' ? 'Pending Dosen PA' :
                type === 'kaprodi' ? 'Pending Kaprodi' :
                type === 'wadek1' ? 'Pending Wadek 1' : 'Pending Staff TU';

            document.querySelector('#pendingModal .modal-title').textContent = 'Detail ' + title;
            document.getElementById('pendingContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

            modal.show();

            fetch(`/dashboard/pending/${type}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length > 0) {
                        html = '<div class="table-responsive"><table class="table table-striped">';
                        html += '<thead><tr><th>Mahasiswa</th><th>Jenis Surat</th><th>Tanggal Pengajuan</th>';

                        // Tambahkan kolom "Dosen PA" atau "Kaprodi" jika perlu
                        if (type === 'dosen_pa') {
                            html += '<th>Dosen PA</th>';
                        } else if (type === 'kaprodi') {
                            html += '<th>Kaprodi</th>';
                        }

                        html += '<th>Status</th></tr></thead><tbody>';

                        data.forEach(item => {
                            const mahasiswaName = item.mahasiswa?.user?.name || 'Unknown';
                            const jenisSurat = item.jenis_surat?.nama || 'Unknown';
                            const createdAt = new Date(item.created_at).toLocaleDateString('id-ID');

                            let badgeClass = 'bg-secondary';
                            switch (item.status) {
                                case 'diajukan':
                                    badgeClass = 'bg-warning text-dark';
                                    break;
                                case 'diproses':
                                    badgeClass = 'bg-info text-dark';
                                    break;
                                case 'disetujui':
                                    badgeClass = 'bg-success text-white';
                                    break;
                                case 'ditolak':
                                    badgeClass = 'bg-danger text-white';
                                    break;
                            }

                            html += `<tr>
                        <td>${mahasiswaName}</td>
                        <td>${jenisSurat}</td>
                        <td>${createdAt}</td>`;

                            // Tampilkan nama dosen pa / kaprodi sesuai type
                            if (type === 'dosen_pa') {
                                const dosenPA = item.dosen_p_a?.name || '-';
                                html += `<td>${dosenPA}</td>`;
                            } else if (type === 'kaprodi') {
                                const kaprodi = item.kaprodi?.name || '-';
                                html += `<td>${kaprodi}</td>`;
                            }

                            html += `<td><span class="badge fs-6 ${badgeClass}">${item.status}</span></td>
                    </tr>`;
                        });

                        html += '</tbody></table></div>';
                    } else {
                        html = '<p class="text-center text-muted">Tidak ada data pending approval</p>';
                    }

                    document.getElementById('pendingContent').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('pendingContent').innerHTML =
                        '<p class="text-center text-danger">Gagal memuat data</p>';
                });
        }
        // Auto refresh every 5 minutes
        setInterval(function() {
            refreshStats();
        }, 300000);
    </script>
@endpush
