@extends('layouts.default')

@section('content')
    <div class="container">
        <h3>Data Kaprodi Tahunan</h3>

        <button class="btn btn-primary mb-3" id="createNew">Tambah Data</button>

        <div class="card card-body">

            <table class="table table-bordered" id="kaprodiTable">
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

    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="formInput">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Kaprodi Tahunan</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label>Tahun Angkatan</label>
                            <input type="text" name="tahun_angkatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Prodi</label>
                            <select name="prodi_id" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>User</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#kaprodiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kaprodi-tahunan.index') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'tahun_angkatan'
                    },
                    {
                        data: 'prodi'
                    }, // tampilkan nama prodi
                    {
                        data: 'user'
                    }, // tampilkan nama user
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]

            });

            $('#createNew').click(function() {
                $('#formInput').trigger("reset");
                $('#ajaxModal').modal('show');
            });

            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.get("{{ url('kaprodi-tahunan') }}/" + id + "/edit", function(data) {
                    $('#ajaxModal').modal('show');
                    $('#id').val(data.id);
                    $('[name=tahun_angkatan]').val(data.tahun_angkatan);
                    $('[name=prodi_id]').val(data.prodi_id);
                    $('[name=user_id]').val(data.user_id);

                })
            });

            $('#formInput').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('kaprodi-tahunan.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function() {
                        $('#ajaxModal').modal('hide');
                        table.ajax.reload();
                    }
                });
            });

            $('body').on('click', '.delete', function() {
                if (confirm("Yakin hapus data ini?")) {
                    $.ajax({
                        url: "{{ url('kaprodi-tahunan') }}/" + $(this).data("id"),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            table.ajax.reload();
                        }
                    });
                }
            });
        });
    </script>
@endpush
