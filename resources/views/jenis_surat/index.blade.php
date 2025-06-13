@extends('layouts.default')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Data Jenis Surat</h4>
            <button class="btn btn-primary mb-0" id="createNewJenis">
                <i class="ti ti-plus me-2"></i>Tambah Jenis Surat
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="jenisTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jenis Surat</th>
                        <th>Deskripsi</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


    <!-- Modal Form -->
    <div class="modal fade" id="ajaxModel" tabindex="-1" aria-labelledby="ajaxModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="jenisForm" name="jenisForm" class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ajaxModelLabel">Tambah Jenis Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="jenis_id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Jenis Surat</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   placeholder="Masukkan Nama Jenis Surat" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                      placeholder="Masukkan Deskripsi"></textarea>
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
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let table = $('#jenisTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jenis-surat.data') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nama', name: 'nama'},
                {data: 'deskripsi', name: 'deskripsi'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#createNewJenis').click(function () {
            $('#saveBtn').val("create-jenis");
            $('#jenis_id').val('');
            $('#jenisForm').trigger("reset");
            $('#ajaxModelLabel').html("Tambah Jenis Surat");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.edit', function () {
            let id = $(this).data('id');
            $.get("{{ url('jenis-surat') }}/" + id, function (data) {
                $('#ajaxModelLabel').html("Edit Jenis Surat");
                $('#saveBtn').val("edit-jenis");
                $('#ajaxModel').modal('show');
                $('#jenis_id').val(data.id);
                $('#nama').val(data.nama);
                $('#deskripsi').val(data.deskripsi);
            });
        });

        $('#jenisForm').submit(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Menyimpan...');

            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('jenis-surat.store') }}",
                type: "POST",
                dataType: 'json',
                success: function () {
                    $('#jenisForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#saveBtn').html('Simpan');
                    table.draw();
                },
                error: function (xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                    $('#saveBtn').html('Simpan');
                }
            });
        });

        $('body').on('click', '.delete', function () {
            if (confirm("Yakin ingin menghapus data ini?")) {
                let id = $(this).data("id");

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('jenis-surat') }}/" + id,
                    success: function () {
                        table.draw();
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });

    });
</script>
@endpush
