@extends('layouts.default')

@section('content')
    <div class="container">
        <h3>Data Fakultas</h3>

        <button class="btn btn-primary mb-3" id="createNewFakultas">Tambah Fakultas</button>
        <div class="card card-body">

            <table class="table table-bordered" id="fakultasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Fakultas</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="ajaxModel" tabindex="-1" aria-labelledby="ajaxModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="fakultasForm" name="fakultasForm" class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ajaxModelLabel">Tambah Fakultas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="fakultas_id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Fakultas</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama Fakultas" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')



    <script>
        $(function() {
            // Setup CSRF token for all ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#fakultasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('fakultas.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Show modal to create new Fakultas
            $('#createNewFakultas').click(function() {
                $('#saveBtn').val("create-fakultas");
                $('#fakultas_id').val('');
                $('#fakultasForm').trigger("reset");
                $('#ajaxModelLabel').html("Tambah Fakultas");
                $('#ajaxModel').modal('show');
            });

            // Edit button click
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.get("{{ url('fakultas') }}" + '/' + id, function(data) {
                    $('#ajaxModelLabel').html("Edit Fakultas");
                    $('#saveBtn').val("edit-fakultas");
                    $('#ajaxModel').modal('show');
                    $('#fakultas_id').val(data.id);
                    $('#nama').val(data.nama);
                });
            });

            // Save or Update Fakultas
            $('#fakultasForm').submit(function(e) {
                e.preventDefault();
                $('#saveBtn').html('Menyimpan...');

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('fakultas.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#fakultasForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Simpan');
                        table.draw();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                        $('#saveBtn').html('Simpan');
                    }
                });
            });

            // Delete Fakultas
            $('body').on('click', '.delete', function() {
                if (confirm("Apakah kamu yakin ingin menghapus data ini?") == true) {
                    var id = $(this).data('id');

                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('fakultas') }}" + '/' + id,
                        success: function(data) {
                            table.draw();
                        },
                        error: function(data) {
                            alert('Error saat menghapus data');
                        }
                    });
                }
            });

        });
    </script>
@endpush
