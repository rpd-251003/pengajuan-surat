@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Template Surat</h4>
                </div>

                <form action="{{ route('template.update', $template->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Form Kiri -->
                            <div class="col-lg-4 col-md-12">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="jenis_surat_id" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                        <select class="form-select" id="jenis_surat_id" name="jenis_surat_id" required>
                                            <option value="">Pilih Jenis Surat</option>
                                            @foreach($jenisSurats as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    data-fields="{{ $jenis->fields->toJson() }}"
                                                    {{ $template->jenis_surat_id == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_surat_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="nama_template" class="form-label">Nama Template <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_template" name="nama_template"
                                               value="{{ old('nama_template', $template->nama_template) }}" required>
                                        @error('nama_template')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="orientation" class="form-label">Orientasi <span class="text-danger">*</span></label>
                                        <select class="form-select" id="orientation" name="orientation" required>
                                            <option value="portrait" {{ old('orientation', $template->orientation) == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                            <option value="landscape" {{ old('orientation', $template->orientation) == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="paper_size" class="form-label">Ukuran Kertas <span class="text-danger">*</span></label>
                                        <select class="form-select" id="paper_size" name="paper_size" required>
                                            <option value="A4" {{ old('paper_size', $template->paper_size) == 'A4' ? 'selected' : '' }}>A4</option>
                                            <option value="A5" {{ old('paper_size', $template->paper_size) == 'A5' ? 'selected' : '' }}>A5</option>
                                            <option value="Letter" {{ old('paper_size', $template->paper_size) == 'Letter' ? 'selected' : '' }}>Letter</option>
                                            <option value="Legal" {{ old('paper_size', $template->paper_size) == 'Legal' ? 'selected' : '' }}>Legal</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="header_image" class="form-label">Gambar Header (Opsional)</label>
                                        @if($template->header_image)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($template->header_image) }}" alt="Header Image"
                                                     class="img-thumbnail" style="max-height: 100px;">
                                                <div class="form-text">Gambar header saat ini</div>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="header_image" name="header_image" accept="image/*">
                                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</small>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="footer_text" class="form-label">Footer Text</label>
                                        <textarea class="form-control" id="footer_text" name="footer_text" rows="2">{{ old('footer_text', $template->footer_text) }}</textarea>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                   {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Set sebagai template aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Variables -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Variabel yang Tersedia</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="availableVariables">
                                            <div class="mb-2"><strong>Variabel Default:</strong></div>
                                            <div class="variable-list">
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="tanggal_surat">tanggal_surat</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="nomor_surat">nomor_surat</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="nama_mahasiswa">nama_mahasiswa</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="nim">nim</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="prodi">prodi</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="fakultas">fakultas</span>
                                                <span class="badge bg-primary me-1 mb-1 variable-badge" data-variable="tahun_angkatan">tahun_angkatan</span>
                                            </div>
                                            <div id="dynamicVariables"></div>
                                            <small class="text-muted d-block mt-2">Klik variabel untuk menambahkan ke template</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template Shortcuts -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Template Cepat</h6>
                                    </div>
                                    <div class="card-body">
                                        <button type="button" class="btn btn-sm btn-outline-info mb-2 w-100" onclick="loadTemplate('keterangan')">
                                            <i class="ti ti-file-text"></i> Template Surat Keterangan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info mb-2 w-100" onclick="loadTemplate('pengantar')">
                                            <i class="ti ti-file-arrow-right"></i> Template Surat Pengantar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info mb-2 w-100" onclick="loadTemplate('rekomendasi')">
                                            <i class="ti ti-file-check"></i> Template Surat Rekomendasi
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Editor Area -->
                            <div class="col-lg-8 col-md-12">
                                <div class="row">
                                    <!-- HTML Editor -->
                                    <div class="col-12 mb-3">
                                        <label for="template_content" class="form-label">Konten Template <span class="text-danger">*</span></label>
                                        <div class="editor-container">
                                            <textarea id="template_content" name="template_content" class="tinymce-editor">{{ old('template_content', $template->template_content) }}</textarea>
                                        </div>
                                        @error('template_content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- CSS Styles (Collapsed by default) -->
                                    <div class="col-12 mb-3">
                                        <div class="card">
                                            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#cssSection" style="cursor: pointer;">
                                                <h6 class="card-title mb-0">
                                                    <i class="ti ti-code"></i> CSS Kustom (Opsional)
                                                    <i class="ti ti-chevron-down float-end"></i>
                                                </h6>
                                            </div>
                                            <div id="cssSection" class="collapse">
                                                <div class="card-body">
                                                    <textarea class="form-control" id="css_styles" name="css_styles" rows="8" placeholder="Masukkan CSS untuk styling tambahan...">{{ old('css_styles', $template->css_styles) }}</textarea>
                                                    @error('css_styles')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('template.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-info me-2" id="previewBtn">
                                    <i class="ti ti-eye me-2"></i>Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-2"></i>Update Template
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" class="preview-content">
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

@push('styles')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/4c9ltf17kk5jhifa3he447fl5yodnffh935nqim932yd0pj5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<style>
.variable-badge {
    cursor: pointer;
    transition: all 0.2s;
}

.variable-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.variable-list {
    max-height: 200px;
    overflow-y: auto;
}

.preview-content {
    max-height: 70vh;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 20px;
    background: white;
}

.editor-container {
    border: 1px solid #ddd;
    border-radius: 4px;
}

.img-thumbnail {
    max-width: 100%;
    height: auto;
}

@media (max-width: 768px) {
    .card-body .row .col-lg-4 {
        order: 2;
        margin-top: 20px;
    }

    .card-body .row .col-lg-8 {
        order: 1;
    }

    .card-footer .d-flex {
        flex-direction: column;
        gap: 10px;
    }

    .card-footer .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .modal-xl {
        max-width: 95%;
        margin: 5px;
    }

    .variable-badge {
        font-size: 0.7rem;
        margin-bottom: 0.25rem;
    }
}

/* Print styles for preview */
@media print {
    body {
        margin: 0;
        font-size: 11px;
        line-height: 1.4;
    }

    .preview-content {
        border: none;
        padding: 0;
        max-height: none;
        overflow: visible;
    }
}
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/template-editor.js') }}"></script>

@endpush
