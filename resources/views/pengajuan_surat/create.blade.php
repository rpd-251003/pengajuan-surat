@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card card-body">
            <h3><i class="fas fa-file-alt me-2"></i>Pengajuan Surat</h3>



            <form id="pengajuanSuratForm" method="POST" action="{{ route('pengajuan_surat.store') }}"
                enctype="multipart/form-data">
                @csrf

                <!-- Hidden input untuk menyimpan jenis surat yang dipilih -->
                <input type="hidden" name="jenis_surat" id="jenis_surat_input">

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Jenis Surat</label>
                    <div class="row" id="jenis_surat_buttons">
                        @foreach ($jenisSurats as $surat)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="surat-button p-3 rounded text-center" data-id="{{ $surat->id }}"
                                    data-nama="{{ $surat->nama }}">
                                    <div class="surat-icon">
                                        <i class="{{ $surat->icon ?? 'fas fa-file-alt' }} text-primary"></i>
                                    </div>
                                    <div class="fw-bold">{{ $surat->nama }}</div>
                                    <small
                                        class="text-muted">{{ $surat->deskripsi_singkat ?? 'Klik untuk memilih' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Info surat yang dipilih -->
                <div id="selected_surat_info" class="alert alert-info" style="display: none;">
                    <h6><i class="fas fa-info-circle me-2"></i>Surat yang dipilih:</h6>
                    <p id="selected_surat_name" class="mb-0 fw-bold"></p>
                </div>

                <div id="deskripsi_surat" class="mt-3"></div>

                <!-- Dynamic Form Fields Container -->
                <div id="dynamic_fields_container" style="display: none;">
                    <h5 class="mb-3">
                        <i class="fas fa-edit me-2"></i>Form Pengajuan
                        <small class="text-muted">(Data nama dan NIM sudah otomatis terisi)</small>
                    </h5>
                    <!-- Info Mahasiswa (Auto Fields Preview) -->
                    <div class="alert alert-light border-start border-primary border-4 mb-4">
                        <h6 class="mb-3"><i class="fas fa-user me-2"></i>Informasi Mahasiswa</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Nama:</strong> {{ $userData['nama'] }}
                                </div>
                                <div class="mb-2">
                                    <strong>NIM:</strong> {{ $userData['nim'] }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Fakultas:</strong> {{ $userData['fakultas'] }}
                                </div>
                                <div class="mb-2">
                                    <strong>Program Studi:</strong> {{ $userData['prodi'] }}
                                </div>
                                @if ($userData['angkatan'])
                                    <div class="mb-2">
                                        <strong>Angkatan:</strong> {{ $userData['angkatan'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Informasi ini akan otomatis terisi di setiap pengajuan surat.
                        </small>
                    </div>
                    <div id="dynamic_fields"></div>
                </div>



                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-bold">Keterangan Tambahan <span
                            class="text-muted">(opsional)</span></label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                        placeholder="Masukkan keterangan tambahan jika diperlukan..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" disabled id="submit_btn">
                    <i class="fas fa-paper-plane me-2"></i>Ajukan Surat
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .surat-button {
            border: 2px solid #e9ecef;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .surat-button:hover {
            border-color: #007bff;
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.1);
        }

        .surat-button.selected {
            border-color: #007bff;
            background: #e3f2fd;
            color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }

        .surat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .surat-button.selected .surat-icon i {
            color: #007bff !important;
        }

        #selected_surat_info,
        #dynamic_fields_container {
            animation: fadeIn 0.3s ease;
        }

        .dynamic-field {
            margin-bottom: 1rem;
        }

        .checkbox-group {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .checkbox-group .form-check {
            margin-bottom: 0.5rem;
        }

        .checkbox-group .form-check:last-child {
            margin-bottom: 0;
        }

        .auto-field-info {
            background: linear-gradient(45deg, #e3f2fd, #f3e5f5);
            border-left: 4px solid #2196f3;
        }

        .border-start {
            border-left-width: 0.25rem !important;
        }

        .border-4 {
            border-width: 0.25rem !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .surat-button {
                min-height: 80px;
            }

            .surat-icon {
                font-size: 24px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const suratButtons = $('.surat-button');
            const hiddenInput = $('#jenis_surat_input');
            const selectedInfo = $('#selected_surat_info');
            const selectedName = $('#selected_surat_name');
            const submitBtn = $('#submit_btn');
            const dynamicFieldsContainer = $('#dynamic_fields_container');
            const dynamicFields = $('#dynamic_fields');

            // User data from server (for auto fields)
            const userData = @json($userData);

            // Event handler untuk tombol jenis surat
            suratButtons.on('click', function() {
                // Hapus class selected dari semua tombol
                suratButtons.removeClass('selected');

                // Tambah class selected ke tombol yang diklik
                $(this).addClass('selected');

                // Get data dari tombol yang diklik
                const suratId = $(this).data('id');
                const suratNama = $(this).data('nama');

                // Set nilai ke hidden input
                hiddenInput.val(suratId);

                // Tampilkan info surat yang dipilih
                selectedName.text(suratNama);
                selectedInfo.show();

                // Enable submit button
                submitBtn.prop('disabled', false);

                // Load deskripsi surat dan form fields via AJAX
                loadSuratData(suratId);

                // Scroll otomatis ke dynamic fields setelah delay singkat
                setTimeout(function() {
                    scrollToDynamicFields();
                }, 300);
            });

            // Fungsi untuk load deskripsi surat dan dynamic fields
            function loadSuratData(jenisSuratId) {
                if (jenisSuratId) {
                    $.ajax({
                        url: '{{ route('pengajuan_surat.deskripsi') }}',
                        type: 'GET',
                        data: {
                            jenis_surat: jenisSuratId
                        },
                        success: function(response) {
                            // Replace newlines with <br>
                            const formattedDeskripsi = response.deskripsi.replace(/\n/g, '<br>');
                            $('#deskripsi_surat').html(
                                `<div class="alert alert-primary">
                                    <h6><i class="fas fa-list-check me-2"></i>Persyaratan dan Tata Cara:</h6>
                                    ${formattedDeskripsi}
                                </div>`
                            );

                            // Generate dynamic form fields (excluding auto fields)
                            generateDynamicFields(response.fields);
                        },
                        error: function() {
                            $('#deskripsi_surat').html(
                                '<div class="alert alert-warning">Gagal memuat deskripsi surat.</div>'
                            );
                            dynamicFieldsContainer.hide();
                        }
                    });
                } else {
                    $('#deskripsi_surat').html('');
                    dynamicFieldsContainer.hide();
                }
            }

            // Generate dynamic form fields (excluding auto fields)
            function generateDynamicFields(fields) {
                if (!fields || fields.length === 0) {
                    dynamicFieldsContainer.hide();
                    return;
                }

                let html = '';

                // Filter out auto fields yang sudah dihandle otomatis
                const autoFields = ['nama', 'nim', 'fakultas', 'prodi', 'angkatan'];
                const dynamicFieldsOnly = fields.filter(field => !autoFields.includes(field.field_name));

                if (dynamicFieldsOnly.length === 0) {
                    // Jika tidak ada dynamic fields, tampilkan pesan
                    html = `<div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Semua data untuk surat ini sudah otomatis terisi dari profil Anda.
                    </div>`;
                } else {
                    dynamicFieldsOnly.forEach(function(field) {
                        html += generateFieldHtml(field);
                    });
                }

                dynamicFields.html(html);
                dynamicFieldsContainer.show();
            }

            // Generate HTML for individual field
            function generateFieldHtml(field) {
                const required = field.is_required ? 'required' : '';
                const requiredMark = field.is_required ? '<span class="text-danger">*</span>' : '';
                const placeholder = field.placeholder || '';

                let fieldHtml = `<div class="dynamic-field">
                    <label for="${field.field_name}" class="form-label fw-bold">
                        ${field.field_label} ${requiredMark}
                    </label>`;

                switch (field.field_type) {
                    case 'text':
                    case 'email':
                    case 'number':
                        fieldHtml += `<input type="${field.field_type}" class="form-control"
                                     id="${field.field_name}" name="${field.field_name}"
                                     placeholder="${placeholder}" ${required}>`;
                        break;

                    case 'textarea':
                        fieldHtml += `<textarea class="form-control" id="${field.field_name}"
                                     name="${field.field_name}" rows="3"
                                     placeholder="${placeholder}" ${required}></textarea>`;
                        break;

                    case 'select':
                        fieldHtml += `<select class="form-select" id="${field.field_name}"
                                     name="${field.field_name}" ${required}>
                                     <option value="">Pilih...</option>`;
                        if (field.field_options) {
                            Object.entries(field.field_options).forEach(([key, value]) => {
                                fieldHtml += `<option value="${key}">${value}</option>`;
                            });
                        }
                        fieldHtml += `</select>`;
                        break;

                    case 'checkbox':
                        fieldHtml += `<div class="checkbox-group">`;
                        if (field.field_options) {
                            Object.entries(field.field_options).forEach(([key, value]) => {
                                fieldHtml += `<div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="${field.field_name}[]" value="${key}"
                                           id="${field.field_name}_${key}" ${required}>
                                    <label class="form-check-label" for="${field.field_name}_${key}">
                                        ${value}
                                    </label>
                                </div>`;
                            });
                        }
                        fieldHtml += `</div>`;

                        // Add helper text for required checkboxes
                        if (field.is_required) {
                            fieldHtml += `<small class="form-text text-muted">Pilih minimal 1 item</small>`;
                        }
                        break;

                    case 'radio':
                        if (field.field_options) {
                            Object.entries(field.field_options).forEach(([key, value]) => {
                                fieldHtml += `<div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="${field.field_name}" value="${key}"
                                           id="${field.field_name}_${key}" ${required}>
                                    <label class="form-check-label" for="${field.field_name}_${key}">
                                        ${value}
                                    </label>
                                </div>`;
                            });
                        }
                        break;

                    case 'file':
                        fieldHtml += `<input type="file" class="form-control"
                 id="${field.field_name}" name="${field.field_name}" ${required}>
                 <small class="form-text text-muted">Maksimal 5MB</small>`;
                        break;
                }

                fieldHtml += `</div>`;
                return fieldHtml;
            }

            // Fungsi untuk scroll ke dynamic fields
            function scrollToDynamicFields() {
                if (dynamicFieldsContainer.is(':visible')) {
                    $('html, body').animate({
                        scrollTop: dynamicFieldsContainer.offset().top - 100
                    }, 600);
                }
            }
            $(document).on('change', 'input[type="file"]', function() {
                const file = this.files[0];
                if (file && file.size > 5 * 1024 * 1024) { // 5MB
                    $(this).val('');
                    alert('File terlalu besar! Maksimal 5MB.');
                }
            });
            // Form validation before submit
            // Replace the form submit handler in pengajuan_surat/create.blade.php
            // Form validation before submit
            $('#pengajuanSuratForm').on('submit', function(e) {
                console.log('=== FORM SUBMIT START ===');

                // Log all form data
                let formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                // Basic validation for required dynamic fields
                let isValid = true;
                let errorMessage = '';

                $(this).find('input[required], select[required], textarea[required]').each(function() {
                    console.log('Checking field:', $(this).attr('name'), 'Value:', $(this).val());
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        const label = $(this).closest('.dynamic-field').find('label').text()
                            .replace('*', '').trim();
                        errorMessage += `${label} wajib diisi.\n`;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Checkbox validation for required fields
                $('.checkbox-group').each(function() {
                    const checkboxes = $(this).find('input[type="checkbox"]');
                    const firstCheckbox = checkboxes.first();

                    if (firstCheckbox.prop('required')) {
                        const isAnyChecked = checkboxes.is(':checked');
                        console.log('Checkbox group validation:', {
                            'name': firstCheckbox.attr('name'),
                            'required': firstCheckbox.prop('required'),
                            'anyChecked': isAnyChecked
                        });

                        if (!isAnyChecked) {
                            isValid = false;
                            $(this).addClass('border-danger');
                            const label = $(this).closest('.dynamic-field').find('label').text()
                                .replace('*', '').trim();
                            errorMessage += `${label} harus dipilih minimal 1 item.\n`;
                        } else {
                            $(this).removeClass('border-danger');
                        }
                    }
                });

                console.log('Validation result:', {
                    isValid,
                    errorMessage
                });

                if (!isValid) {
                    e.preventDefault();
                    console.log('Validation failed, preventing submit');
                    Swal.fire({
                        icon: 'error',
                        title: 'Form tidak lengkap!',
                        text: 'Harap isi semua field yang wajib diisi:\n\n' + errorMessage,
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Check if jenis_surat is selected
                const jenisSurat = $('#jenis_surat_input').val();
                console.log('Jenis surat selected:', jenisSurat);

                if (!jenisSurat) {
                    e.preventDefault();
                    console.log('No jenis surat selected');
                    Swal.fire({
                        icon: 'error',
                        title: 'Pilih Jenis Surat!',
                        text: 'Harap pilih jenis surat terlebih dahulu.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                console.log('=== FORM SUBMIT SUCCESS ===');

                // Show loading state
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...');
            });

            // Clear validation styling on input
            $(document).on('input change', 'input, select, textarea', function() {
                $(this).removeClass('is-invalid');
            });

            $(document).on('change', 'input[type="checkbox"]', function() {
                $(this).closest('.checkbox-group').removeClass('border-danger');
            });
        });
    </script>
@endpush
