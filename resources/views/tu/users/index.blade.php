@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Manajemen Users</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" onclick="importMahasiswa()">
                                <i class="ti ti-file-import me-2"></i>Import Mahasiswa
                            </button>
                            <button type="button" class="btn btn-info" onclick="exportMahasiswa()">
                                <i class="ti ti-file-export me-2"></i>Export Mahasiswa
                            </button>
                            <button type="button" class="btn btn-warning" onclick="downloadSample()">
                                <i class="ti ti-download me-2"></i>Download Sample Import Data
                            </button>
                            <button type="button" class="btn btn-primary" onclick="createUser()">
                                <i class="ti ti-plus me-2"></i>Tambah User
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="usersTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>NIM / NIP / NIDN</th>
                                    <th>Tanggal Dibuat</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="import_file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Format file yang didukung: .xlsx, .xls, .csv<br>
                                <strong>Kolom yang diperlukan:</strong> NIM, Nama, Password
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6>Format NIM yang didukung:</h6>
                            <ul class="mb-0">
                                <li><strong>21xx</strong> = Teknik Elektro</li>
                                <li><strong>22xx</strong> = Teknik Industri</li>
                                <li><strong>23xx</strong> = Teknik Teknologi Informasi</li>
                                <li><strong>24xx</strong> = Sistem Informasi</li>
                                <li><strong>25xx</strong> = Teknik Mesin</li>
                            </ul>
                            <small>Contoh: 2021240001 = Angkatan 2021, Prodi Sistem Informasi</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-upload me-2"></i>Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role_badge',
                        name: 'role'
                    },
                    {
                        data: 'nomor_identifikasi',
                        name: 'nomor_identifikasi'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    processing: "Memuat data...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });

        // Create User
        function createUser() {
            $.ajax({
                url: "{{ route('users.create') }}",
                type: 'GET',
                success: function(response) {
                    $('#modalContent').html(response.html);
                    $('#userModalLabel').text('Tambah User');
                    $('#userModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat memuat form', 'error');
                }
            });
        }

        // Show User
        function showUser(id) {
            $.ajax({
                url: "{{ url('users') }}/" + id,
                type: 'GET',
                success: function(response) {
                    $('#modalContent').html(response.html);
                    $('#userModalLabel').text('Detail User');
                    $('#userModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        }

        // Edit User
        function editUser(id) {
            $.ajax({
                url: "{{ url('users') }}/" + id + "/edit",
                type: 'GET',
                success: function(response) {
                    $('#modalContent').html(response.html);
                    $('#userModalLabel').text('Edit User');
                    $('#userModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat memuat form', 'error');
                }
            });
        }

        // Delete User
        function deleteUser(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data user akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('users') }}/" + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                $('#usersTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    });
                }
            });
        }

        // Submit Form (Create/Update)
        $(document).on('submit', '#userForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let url = $(this).attr('action');
            let method = $(this).attr('method');

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#userModal').modal('hide');
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#usersTable').DataTable().ajax.reload();
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.after('<div class="invalid-feedback">' + value[0] +
                                    '</div>');
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data', 'error');
                }
            });
        });

        // Import Mahasiswa
        function importMahasiswa() {
            $('#importModal').modal('show');
        }

        // Export Mahasiswa
        function exportMahasiswa() {
            window.location.href = "{{ route('users.export') }}";
        }

        // Download Sample
        function downloadSample() {
            window.location.href = "{{ route('users.sample') }}";
        }

        // Handle Import Form
        $(document).on('submit', '#importForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            submitBtn.html('<i class="ti ti-loader me-2"></i>Importing...').prop('disabled', true);

            $.ajax({
                url: "{{ route('users.import') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#importModal').modal('hide');
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#usersTable').DataTable().ajax.reload();
                        $('#importForm')[0].reset();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat import data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    </script>
@endpush
