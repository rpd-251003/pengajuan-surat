@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Data Dosen PA Tahunan</h4>
                <button type="button" class="btn btn-primary mb-3" id="btnAdd">
                    <i class="ti ti-plus me-2"></i>Tambah User
                </button>

            </div>

            <div class="card-body">
                <table class="table table-bordered" id="dosenPaTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Angkatan</th>
                            <th>Prodi</th>
                            <th>User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="dosenPaModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formDosenPa" method="POST">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Form Dosen PA Tahunan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tahun_angkatan" class="form-label">Tahun Angkatan</label>
                            <input type="text" name="tahun_angkatan" id="tahun_angkatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="prodi_id" class="form-label">Prodi</label>
                            <select name="prodi_id" id="prodi_id" class="form-select" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Dosen</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#dosenPaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dosen-pa-tahunan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tahun_angkatan',
                        name: 'tahun_angkatan'
                    },
                    {
                        data: 'prodi',
                        name: 'prodi'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Show Modal for Add
            $('#btnAdd').click(function() {
                $('#formDosenPa')[0].reset();
                $('#id').val('');
                $('#dosenPaModal').modal('show');
                $('#modalTitle').text('Tambah Data Dosen PA Tahunan');
            });

            // Store or Update
            $('#formDosenPa').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('dosen-pa-tahunan.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#dosenPaModal').modal('hide');
                        table.ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert(
                            'Terjadi kesalahan. Pastikan semua data telah diisi dengan benar.'
                        );
                    }
                });
            });

            // Edit
            $('#dosenPaTable').on('click', '.edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('dosen-pa-tahunan') }}/" + id + "/edit", function(data) {
                    $('#id').val(data.id);
                    $('#tahun_angkatan').val(data.tahun_angkatan);
                    $('#prodi_id').val(data.prodi_id);
                    $('#user_id').val(data.user_id);
                    $('#dosenPaModal').modal('show');
                    $('#modalTitle').text('Edit Data Dosen PA Tahunan');
                });
            });

            // Delete
            $('#dosenPaTable').on('click', '.delete', function() {
                if (confirm("Yakin ingin menghapus data ini?")) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('dosen-pa-tahunan') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.ajax.reload();
                            alert(response.success);
                        }
                    });
                }
            });
        });
    </script>
@endpush
