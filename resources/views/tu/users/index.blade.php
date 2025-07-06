@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Manajemen Users</h4>
                        <button type="button" class="btn btn-primary" onclick="createUser()">
                            <i class="ti ti-plus me-2"></i>Tambah User
                        </button>
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
    </script>
@endpush
