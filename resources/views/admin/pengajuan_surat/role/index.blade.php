@extends('layouts.default')

@section('content')
    <div class="container mt-4">
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
                                            {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : 'N/A' }}</small>
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
                                        <form method="POST" action="{{ route('admin.pengajuan.approve_double', $p->id) }}"
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
