@extends('layouts.default')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Kelola Field: {{ $jenisSurat->nama }}</h4>
                <small class="text-muted">Atur field dinamis untuk jenis surat ini</small>
            </div>
            <div>
                <a href="{{ route('jenis-surat.index') }}" class="btn btn-secondary me-2">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
                <button class="btn btn-primary" id="createNewField">
                    <i class="ti ti-plus me-2"></i>Tambah Field
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="fieldsTable">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Field</th>
                        <th>Label</th>
                        <th>Tipe</th>
                        <th>Required</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="fieldModal" tabindex="-1" aria-labelledby="fieldModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="fieldForm" name="fieldForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldModalLabel">Tambah Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="field_id">
                    <input type="hidden" name="jenis_surat_id" value="{{ $jenisSurat->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field_name" class="form-label">Nama Field <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="field_name" name="field_name"
                                       placeholder="Contoh: nama_lengkap" required>
                                <small class="form-text text-muted">Gunakan underscore untuk pemisah kata</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field_label" class="form-label">Label Field <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="field_label" name="field_label"
                                       placeholder="Contoh: Nama Lengkap" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="field_type" class="form-label">Tipe Field <span class="text-danger">*</span></label>
                                <select class="form-select" id="field_type" name="field_type" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="text">Text</option>
                                    <option value="email">Email</option>
                                    <option value="number">Number</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="select">Select Dropdown</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="radio">Radio Button</option>
                                    <option value="file">File Upload</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order"
                                       placeholder="0" min="0">
                                <small class="form-text text-muted">Semakin kecil angka, semakin atas posisinya</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="placeholder" class="form-label">Placeholder</label>
                        <input type="text" class="form-control" id="placeholder" name="placeholder"
                               placeholder="Teks bantuan untuk user">
                    </div>

                    <div class="mb-3" id="field_options_container" style="display: none;">
                        <label for="field_options" class="form-label">Opsi Field</label>
                        <textarea class="form-control" id="field_options" name="field_options" rows="4"
                                  placeholder="Untuk select/checkbox/radio, masukkan opsi satu per baris:&#10;key1:Label 1&#10;key2:Label 2"></textarea>
                        <small class="form-text text-muted">Format: key:label (satu per baris)</small>
                    </div>

                    <div class="mb-3">
                        <label for="validation_rules" class="form-label">Validation Rules</label>
                        <input type="text" class="form-control" id="validation_rules" name="validation_rules"
                               placeholder="Contoh: min:3|max:50">
                        <small class="form-text text-muted">Pisahkan dengan | (pipe). Contoh: min:3|max:50|alpha</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1">
                            <label class="form-check-label" for="is_required">
                                Field ini wajib diisi
                            </label>
                        </div>
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

        let table = $('#fieldsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jenis-surat-fields.index', $jenisSurat->id) }}",
            columns: [
                {data: 'sort_order', name: 'sort_order'},
                {data: 'field_name', name: 'field_name'},
                {data: 'field_label', name: 'field_label'},
                {data: 'field_type', name: 'field_type'},
                {data: 'required_badge', name: 'is_required'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [[0, 'asc']] // Sort by sort_order
        });

        // Show/hide field options based on field type
        $('#field_type').change(function() {
            const fieldType = $(this).val();
            if (['select', 'checkbox', 'radio'].includes(fieldType)) {
                $('#field_options_container').show();
            } else {
                $('#field_options_container').hide();
            }
        });

        $('#createNewField').click(function () {
            $('#saveBtn').val("create-field");
            $('#field_id').val('');
            $('#fieldForm').trigger("reset");
            $('#fieldModalLabel').html("Tambah Field");
            $('#field_options_container').hide();
            $('#fieldModal').modal('show');
        });

        $('body').on('click', '.edit', function () {
            let id = $(this).data('id');
            $.get("{{ url('jenis-surat-fields') }}/" + id, function (data) {
                $('#fieldModalLabel').html("Edit Field");
                $('#saveBtn').val("edit-field");
                $('#fieldModal').modal('show');

                $('#field_id').val(data.id);
                $('#field_name').val(data.field_name);
                $('#field_label').val(data.field_label);
                $('#field_type').val(data.field_type);
                $('#placeholder').val(data.placeholder);
                $('#field_options').val(data.field_options);
                $('#validation_rules').val(data.validation_rules);
                $('#sort_order').val(data.sort_order);
                $('#is_required').prop('checked', data.is_required);

                // Show/hide field options
                if (['select', 'checkbox', 'radio'].includes(data.field_type)) {
                    $('#field_options_container').show();
                }
            });
        });

        $('#fieldForm').submit(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Menyimpan...');

            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('jenis-surat-fields.store') }}",
                type: "POST",
                dataType: 'json',
                success: function () {
                    $('#fieldForm').trigger("reset");
                    $('#fieldModal').modal('hide');
                    $('#saveBtn').html('Simpan');
                    table.draw();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Field berhasil disimpan',
                        timer: 2000
                    });
                },
                error: function (xhr) {
                    let errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg
                    });
                    $('#saveBtn').html('Simpan');
                }
            });
        });

        $('body').on('click', '.delete', function () {
            let id = $(this).data("id");

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Field yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('jenis-surat-fields') }}/" + id,
                        success: function () {
                            table.draw();
                            Swal.fire('Terhapus!', 'Field berhasil dihapus.', 'success');
                        },
                        error: function () {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
