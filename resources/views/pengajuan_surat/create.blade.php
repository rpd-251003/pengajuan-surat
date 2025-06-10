@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="card card-body">
            <h3><i class="fas fa-file-alt me-2"></i>Pengajuan Surat</h3>

            <form id="pengajuanSuratForm" method="POST" action="{{ route('pengajuan_surat.store') }}">
                @csrf

                <!-- Hidden input untuk menyimpan jenis surat yang dipilih -->
                <input type="hidden" name="jenis_surat" id="jenis_surat_input">

                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Jenis Surat</label>
                    <div class="row" id="jenis_surat_buttons">
                        @foreach ($jenisSurats as $surat)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="surat-button p-3 rounded text-center"
                                     data-id="{{ $surat->id }}"
                                     data-nama="{{ $surat->nama }}">
                                    <div class="surat-icon">
                                        <i class="{{ $surat->icon ?? 'fas fa-file-alt' }} text-primary"></i>
                                    </div>
                                    <div class="fw-bold">{{ $surat->nama }}</div>
                                    <small class="text-muted">{{ $surat->deskripsi_singkat ?? 'Klik untuk memilih' }}</small>
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

                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-bold">Keterangan <span class="text-muted">(isi sesuai tata cara di atas)</span></label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required
                              placeholder="Masukkan keterangan sesuai jenis surat yang dipilih..."></textarea>
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
            box-shadow: 0 4px 8px rgba(0,123,255,0.1);
        }

        .surat-button.selected {
            border-color: #007bff;
            background: #e3f2fd;
            color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
        }

        .surat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .surat-button.selected .surat-icon i {
            color: #007bff !important;
        }

        #selected_surat_info {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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

                // Load deskripsi surat via AJAX
                loadDeskripsiSurat(suratId);

                // Scroll otomatis ke textarea keterangan setelah delay singkat
                // untuk memberi waktu load deskripsi
                setTimeout(function() {
                    scrollToKeterangan();
                }, 300);
            });

            // Fungsi untuk load deskripsi surat
            function loadDeskripsiSurat(jenisSuratId) {
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
                        },
                        error: function() {
                            $('#deskripsi_surat').html(
                                '<div class="alert alert-warning">Gagal memuat deskripsi surat.</div>'
                            );
                        }
                    });
                } else {
                    $('#deskripsi_surat').html('');
                }
            }

            // Fungsi untuk scroll ke textarea keterangan
            function scrollToKeterangan() {
                const keteranganTextarea = $('#keterangan');

                // Smooth scroll ke textarea
                $('html, body').animate({
                    scrollTop: keteranganTextarea.offset().top - 100 // offset 100px dari atas
                }, 600, function() {
                    // Setelah scroll selesai, fokus ke textarea
                    keteranganTextarea.focus();

                    // Untuk mobile, tampilkan keyboard
                    if (window.innerWidth <= 768) {
                        keteranganTextarea.click();
                    }
                });
            }
        });
    </script>
@endpush
