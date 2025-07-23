@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Template Surat</h4>
                <a href="{{ route('template.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>Tambah Template
                </a>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-responsive" id="templateTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Template</th>
                            <th>Jenis Surat</th>
                            <th>Status</th>
                            <th>Orientasi</th>
                            <th>Ukuran Kertas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-a4">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent" style="max-height: 70vh; overflow-y: auto;">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="printPreview">Print Preview</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#templateTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('template.getData') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama_template',
                        name: 'nama_template'
                    },
                    {
                        data: 'jenis_surat_nama',
                        name: 'jenisSurat.nama'
                    },
                    {
                        data: 'status',
                        name: 'is_active',
                        orderable: false
                    },
                    {
                        data: 'orientation',
                        name: 'orientation'
                    },
                    {
                        data: 'paper_size',
                        name: 'paper_size'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 25,
                language: {
                    processing: "Memproses...",
                    lengthMenu: "Tampilkan _MENU_ data",
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

            // Preview template
            $('body').on('click', '.preview', function() {
                let id = $(this).data('id');

                $.get(`{{ url('template/preview') }}/${id}`, function(data) {
                    $('#previewContent').html(data);
                    $('#previewModal').modal('show');
                }).fail(function() {
                    alert('Gagal memuat preview template');
                });
            });

            // Toggle status
            $('body').on('click', '.toggle-status', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: `{{ url('template/toggle-status') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.success,
                            icon: 'success',
                            timer: 2000
                        });
                    },
                    error: function() {
                        alert('Gagal mengubah status template');
                    }
                });
            });

            $('body').on('click', '.edit', function() {
                let id = $(this).data('id');
                window.location.href = `{{ url('template') }}/${id}/edit`;
            });

            // Delete template
            $('body').on('click', '.delete', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Template yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('template') }}/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                table.ajax.reload();
                                Swal.fire('Terhapus!', response.success, 'success');
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Gagal menghapus template',
                                'error');
                            }
                        });
                    }
                });
            });

            // Print preview
            $('#printPreview').click(function() {
                let printContent = document.getElementById('previewContent').innerHTML;
                let originalContent = document.body.innerHTML;

                document.body.innerHTML = printContent;
                window.print();
                document.body.innerHTML = originalContent;
                location.reload();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 10px;
            }

            .card-header .btn {
                width: 100%;
            }
        }

        /* A4 Paper Modal */
        .modal-a4 {
            max-width: 210mm;
            max-width: calc(210mm + 40px); /* Add padding for scrollbar */
        }
        
        .modal-a4 .modal-dialog {
            margin: 30px auto;
        }

        #previewContent {
            border: 1px solid #ddd;
            padding: 0;
            background: white;
            width: 210mm; /* A4 width */
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .modal-body {
            padding: 20px;
            max-height: 80vh;
            overflow-y: auto;
            background: #f5f5f5;
        }
        
        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            .modal-a4 {
                max-width: 95%;
            }
            
            #previewContent {
                width: 100%;
                transform: scale(0.7);
                transform-origin: top center;
            }
        }
    </style>
@endpush
