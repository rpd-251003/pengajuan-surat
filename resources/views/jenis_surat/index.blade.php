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
                        <th>Fields</th>
                        <th>Alur Persetujuan</th>
                        <th>Perlu Nomor</th>
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

    <!-- Modal Workflow -->
    <div class="modal fade" id="workflowModal" tabindex="-1" aria-labelledby="workflowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="workflowForm" name="workflowForm" class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="workflowModalLabel">Edit Workflow Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="workflow_jenis_id">

                        <div class="mb-3">
                            <label class="form-label">Jenis Surat</label>
                            <p id="workflow_jenis_nama" class="form-control-plaintext fw-bold"></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alur Persetujuan</label>
                            <p class="text-muted small">Pilih role yang diperlukan untuk approval dan urutkan sesuai alur</p>
                            <div class="approval-flow-container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Role Tersedia</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="available-roles">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="dosen_pa" id="role_dosen_pa">
                                                        <label class="form-check-label" for="role_dosen_pa">
                                                            Dosen PA
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="kaprodi" id="role_kaprodi">
                                                        <label class="form-check-label" for="role_kaprodi">
                                                            Kaprodi
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="wadek1" id="role_wadek1">
                                                        <label class="form-check-label" for="role_wadek1">
                                                            Wadek 1
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="tu" id="role_tu">
                                                        <label class="form-check-label" for="role_tu">
                                                            TU
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="bak" id="role_bak">
                                                        <label class="form-check-label" for="role_bak">
                                                            BAK
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Alur Approval</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="approval-flow-list" class="approval-flow-list">
                                                    <!-- Flow items akan diisi oleh JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requires_number_generation" name="requires_number_generation">
                                <label class="form-check-label" for="requires_number_generation">
                                    Memerlukan Penomoran Surat
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveWorkflowBtn">Simpan Workflow</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .fields-container {
        max-height: 200px;
        overflow-y: auto;
    }

    .approval-flow {
        white-space: nowrap;
        overflow-x: auto;
    }

    .approval-flow-list {
        min-height: 200px;
        border: 1px dashed #ddd;
        border-radius: 5px;
        padding: 10px;
    }

    .approval-flow-item {
        cursor: move;
    }

    .approval-flow-item:hover {
        background-color: #f8f9fa;
    }

    .btn-workflow {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-workflow:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .field-name {
        font-weight: 500;
    }

    .available-roles .form-check {
        margin-bottom: 10px;
    }

    .available-roles .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .approval-flow-container .card {
        border: 1px solid #dee2e6;
    }

    .approval-flow-container .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.5rem 1rem;
    }

    .approval-flow-container .card-header h6 {
        margin: 0;
        font-weight: 600;
    }
</style>
@endpush

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
                {data: 'fields_list', name: 'fields_list', orderable: false, searchable: false},
                {data: 'approval_flow', name: 'approval_flow', orderable: false, searchable: false},
                {data: 'requires_number', name: 'requires_number', orderable: false, searchable: false},
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

        // Handle edit workflow
        $('body').on('click', '.edit-workflow', function () {
            let id = $(this).data('id');
            $.get("{{ url('jenis-surat') }}/" + id, function (data) {
                $('#workflowModalLabel').html("Edit Workflow: " + data.nama);
                $('#workflow_jenis_id').val(data.id);
                $('#workflow_jenis_nama').text(data.nama);

                // Reset checkboxes
                $('.available-roles input[type="checkbox"]').prop('checked', false);

                // Set approval flow
                if (data.approval_flow) {
                    updateApprovalFlow(data.approval_flow);
                }

                // Set requires number generation
                $('#requires_number_generation').prop('checked', data.requires_number_generation);

                $('#workflowModal').modal('show');
            });
        });

        // Handle checkbox changes
        $('.available-roles input[type="checkbox"]').change(function() {
            let role = $(this).val();
            if ($(this).is(':checked')) {
                addRoleToFlow(role);
            } else {
                removeRoleFromFlow(role);
            }
        });

        // Update approval flow display
        function updateApprovalFlow(flow) {
            const roleNames = {
                'dosen_pa': 'Dosen PA',
                'kaprodi': 'Kaprodi',
                'wadek1': 'Wadek 1',
                'tu': 'TU',
                'bak': 'BAK'
            };

            let flowHtml = '';
            flow.forEach(function(role, index) {
                // Check the checkbox
                $('#role_' + role).prop('checked', true);

                flowHtml += '<div class="approval-flow-item" data-role="' + role + '">';
                flowHtml += '<div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">';
                flowHtml += '<div><span class="badge bg-primary me-2">' + (index + 1) + '</span>' + roleNames[role] + '</div>';
                flowHtml += '<button type="button" class="btn btn-sm btn-outline-danger remove-role" data-role="' + role + '">×</button>';
                flowHtml += '</div>';
                flowHtml += '</div>';
            });

            $('#approval-flow-list').html(flowHtml);
        }

        function addRoleToFlow(role) {
            const roleNames = {
                'dosen_pa': 'Dosen PA',
                'kaprodi': 'Kaprodi',
                'wadek1': 'Wadek 1',
                'tu': 'TU',
                'bak': 'BAK'
            };

            let currentCount = $('.approval-flow-item').length;
            let flowHtml = '<div class="approval-flow-item" data-role="' + role + '">';
            flowHtml += '<div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">';
            flowHtml += '<div><span class="badge bg-primary me-2">' + (currentCount + 1) + '</span>' + roleNames[role] + '</div>';
            flowHtml += '<button type="button" class="btn btn-sm btn-outline-danger remove-role" data-role="' + role + '">×</button>';
            flowHtml += '</div>';
            flowHtml += '</div>';

            $('#approval-flow-list').append(flowHtml);
        }

        function removeRoleFromFlow(role) {
            $('.approval-flow-item[data-role="' + role + '"]').remove();
            // Re-number the remaining items
            $('.approval-flow-item').each(function(index) {
                $(this).find('.badge').text(index + 1);
            });
        }

        // Handle remove role button
        $('body').on('click', '.remove-role', function() {
            let role = $(this).data('role');
            $('#role_' + role).prop('checked', false);
            removeRoleFromFlow(role);
        });

        // Handle workflow form submission
        $('#workflowForm').submit(function (e) {
            e.preventDefault();
            $('#saveWorkflowBtn').html('Menyimpan...');

            // Get approval flow
            let approvalFlow = [];
            $('.approval-flow-item').each(function() {
                approvalFlow.push($(this).data('role'));
            });

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: $('#workflow_jenis_id').val(),
                approval_flow: approvalFlow,
                requires_number_generation: $('#requires_number_generation').is(':checked') ? 1 : 0
            };

            $.ajax({
                data: formData,
                url: "{{ route('jenis-surat.store') }}",
                type: "POST",
                dataType: 'json',
                success: function () {
                    $('#workflowModal').modal('hide');
                    $('#saveWorkflowBtn').html('Simpan Workflow');
                    table.draw();
                    alert('Workflow berhasil disimpan!');
                },
                error: function (xhr) {
                    console.log(xhr.responseJSON);
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMsg = 'Validation errors:\n';
                        for (let field in xhr.responseJSON.errors) {
                            errorMsg += '- ' + xhr.responseJSON.errors[field].join(', ') + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    }
                    $('#saveWorkflowBtn').html('Simpan Workflow');
                }
            });
        });

    });
</script>
@endpush
