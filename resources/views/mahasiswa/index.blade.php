@extends('layouts.default')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Data Mahasiswa</h4>

        </div>

        <div class="card-body">
            <table id="mahasiswaTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th> @if (Auth::user()->role == 'mahasiswa')
                                                NIM
                                            @else
                                                NIP / NIDN
                                            @endif</th>
                        <th>Fakultas</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formMahasiswa">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nomor Identifikasi</label>
                            <input type="text" name="nomor_identifikasi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Fakultas</label>
                            <select name="fakultas_id" class="form-control" required>
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach ($fakultas as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Prodi</label>
                            <select name="prodi_id" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Angkatan</label>
                            <input type="number" name="angkatan" class="form-control" required min="2000"
                                max="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables -->


    <script>
        $(document).ready(function() {
            $('#mahasiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mahasiswa.index') }}",
                columns: [{
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'user.email',
                        name: 'user.email'
                    },
                    {
                        data: 'user.nomor_identifikasi',
                        name: 'user.nomor_identifikasi'
                    },
                    {
                        data: 'fakultas.nama',
                        name: 'fakultas.nama'
                    },
                    {
                        data: 'prodi.nama',
                        name: 'prodi.nama'
                    },
                    {
                        data: 'angkatan',
                        name: 'angkatan'
                    }
                ]
            });

            $('#btnTambah').on('click', function() {
                $('#formMahasiswa')[0].reset();
                $('#id').val('');
                $('#modalForm').modal('show');
            });

            $('#formMahasiswa').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('mahasiswa.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function() {
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan saat menyimpan.");
                        console.log(xhr.responseText);
                    }
                });
            });

            $('body').on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('mahasiswa') }}/" + id + "/edit", function(data) {
                    $('#id').val(data.id);
                    $('input[name=name]').val(data.name);
                    $('input[name=email]').val(data.email);
                    $('input[name=nomor_identifikasi]').val(data.nomor_identifikasi);
                    $('select[name=fakultas_id]').val(data.fakultas_id);
                    $('select[name=prodi_id]').val(data.prodi_id);
                    $('input[name=angkatan]').val(data.angkatan);
                    $('#modalForm').modal('show');
                });
            });

            $('body').on('click', '.btn-delete', function() {
                if (!confirm("Yakin ingin menghapus data ini?")) return;
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('mahasiswa') }}/" + id,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        table.ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
