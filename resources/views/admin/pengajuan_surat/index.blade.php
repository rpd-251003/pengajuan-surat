@extends('layouts.default')

@section('content')
    <div class="container mt-4">
        <div class="card card-body">

            <h3>Daftar Pengajuan Surat</h3>

            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mahasiswa</th>
                        <th>Jenis Surat</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanSurats as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->mahasiswa->user->name ?? 'N/A' }}</td>
                            <td>{{ $p->jenisSurat->nama ?? 'N/A' }}</td>
                            <td>{!! nl2br(e($p->keterangan)) !!}</td>
                            <td>
                                <button class="btn btn-sm btn-info btn-cek-status" data-bs-toggle="modal"
                                    data-bs-target="#statusModal"
                                    data-pengajuan="{{ json_encode([
                                        'dosen_pa' => ['approved_by' => $p->approved_by_dosen_pa, 'approved_at' => $p->approved_at_dosen_pa],
                                        'kaprodi' => ['approved_by' => $p->approved_by_kaprodi, 'approved_at' => $p->approved_at_kaprodi],
                                        'wadek1' => ['approved_by' => $p->approved_by_wadek1, 'approved_at' => $p->approved_at_wadek1],
                                        'staff_tu' => ['approved_by' => $p->approved_by_staff_tu, 'approved_at' => $p->approved_at_staff_tu],
                                    ]) }}">
                                    Cek Status
                                </button>
                            </td>
                            <td>
                                @php $role = Auth::user()->role; @endphp

                                @if ($role == 'dosen_pa')
                                    <form method="POST" action="{{ route('admin.pengajuan.approve_dosen_pa', $p->id) }}"
                                        class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Yakin approve?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal" data-id="{{ $p->id }}" data-level="dosen_pa">
                                        Reject
                                    </button>
                                @elseif($role == 'kaprodi')
                                    <form method="POST" action="{{ route('admin.pengajuan.approve_kaprodi', $p->id) }}"
                                        class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Yakin approve?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal" data-id="{{ $p->id }}" data-level="kaprodi">
                                        Reject
                                    </button>
                                @elseif($role == 'wadek1')
                                    <form method="POST" action="{{ route('admin.pengajuan.approve_wadek1', $p->id) }}"
                                        class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Yakin approve?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal" data-id="{{ $p->id }}" data-level="wadek1">
                                        Reject
                                    </button>
                                @elseif($role == 'tu')
                                    <form method="POST" action="{{ route('admin.pengajuan.approve_staff_tu', $p->id) }}"
                                        class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Yakin approve?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal" data-id="{{ $p->id }}" data-level="staff_tu">
                                        Reject
                                    </button>
                                @else
                                    <span class="text-muted">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Status Persetujuan Pengajuan Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="statusList">
                        <!-- Status akan diisi via JS -->
                    </ul>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Tombol Cek Status
            document.querySelectorAll('.btn-cek-status').forEach(button => {
                button.addEventListener('click', function() {
                    let data = JSON.parse(this.getAttribute('data-pengajuan'));
                    let statusList = document.getElementById('statusList');
                    statusList.innerHTML = '';

                    // Fungsi buat badge dan ikon
                    function makeStatusItem(levelName, approved_by, approved_at) {
                        let isApproved = approved_by !== null && approved_at !== null;
                        let badgeClass = isApproved ? 'bg-success' : 'bg-primary';
                        let icon = isApproved ?
                            `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                       <path d="M2.5 8a5.5 5.5 0 1 1 11 0 5.5 5.5 0 0 1-11 0zM10.354 5.146a.5.5 0 0 0-.708 0L7 7.793 5.854 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0 0-.708z"/>
                    </svg>` :
                            `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                       <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM4.646 4.646a.5.5 0 1 1 .708-.708L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354z"/>
                    </svg>`;

                        return `<li class="list-group-item d-flex justify-content-between align-items-center">
                            ${levelName}
                            <span class="badge ${badgeClass} rounded-pill">
                                ${isApproved ? 'Disetujui' : 'Belum Disetujui'} &nbsp; ${icon}
                            </span>
                        </li>`;
                    }

                    statusList.innerHTML += makeStatusItem('Dosen PA', data.dosen_pa.approved_by,
                        data.dosen_pa.approved_at);
                    statusList.innerHTML += makeStatusItem('Kaprodi', data.kaprodi.approved_by, data
                        .kaprodi.approved_at);
                    statusList.innerHTML += makeStatusItem('Wadek 1', data.wadek1.approved_by, data
                        .wadek1.approved_at);
                    statusList.innerHTML += makeStatusItem('Staff TU', data.staff_tu.approved_by,
                        data.staff_tu.approved_at);
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
