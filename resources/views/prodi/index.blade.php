@extends('layouts.default')

@section('content')
    <div class="container">
        <h3>Data Prodi</h3>
        <button id="btn-add" class="btn btn-primary mb-3">Tambah Prodi</button>
        <div class="card card-body">

            <table id="prodi-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Prodi</th>
                        <th>Fakultas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="prodiModal" tabindex="-1" aria-labelledby="prodiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="prodiForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Prodi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="prodi_id" name="prodi_id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Prodi</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="fakultas_id" class="form-label">Fakultas</label>
                            <select class="form-select" id="fakultas_id" name="fakultas_id" required>
                                <option value="">-- Pilih Fakultas --</option>
                                {{-- opsi fakultas akan diisi dinamis via ajax --}}
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            var modal = new bootstrap.Modal(document.getElementById('prodiModal'));
            var table = $('#prodi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('prodi.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'fakultas',
                        name: 'fakultas.nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function loadFakultas(selected = '') {
                $.get('/list-fakultas', function(data) {
                    let options = '<option value="">-- Pilih Fakultas --</option>';
                    data.forEach(fakultas => {
                        options +=
                            `<option value="${fakultas.id}" ${selected == fakultas.id ? 'selected' : ''}>${fakultas.nama}</option>`;
                    });
                    $('#fakultas_id').html(options);
                });
            }

            // Open modal untuk tambah data
            $('#btn-add').click(function() {
                $('#prodiForm')[0].reset();
                $('#prodi_id').val('');
                loadFakultas();
                modal.show();
            });

            // Edit button
            $('#prodi-table').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.get('/prodi/' + id + '/edit', function(res) {
                    $('#prodi_id').val(res.prodi.id);
                    $('#nama').val(res.prodi.nama);
                    loadFakultas(res.prodi.fakultas_id);
                    modal.show();
                });
            });

            // Submit form (store/update)
            $('#prodiForm').submit(function(e) {
                e.preventDefault();

                var id = $('#prodi_id').val();
                var url = id ? '/prodi/' + id : '/prodi';
                var type = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: type,
                    data: $(this).serialize(),
                    success: function(res) {
                        modal.hide();
                        table.ajax.reload(null, false);
                        alert(res.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message || 'Terjadi kesalahan');
                    }
                });
            });

            // Delete button
            $('#prodi-table').on('click', '.btn-delete', function() {
                if (!confirm('Yakin ingin menghapus data ini?')) return;

                var id = $(this).data('id');
                $.ajax({
                    url: '/prodi/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        table.ajax.reload(null, false);
                        alert(res.message);
                    },
                    error: function() {
                        alert('Gagal menghapus data!');
                    }
                });
            });
        });
    </script>
@endpush
