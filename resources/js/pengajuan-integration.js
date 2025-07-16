
class TemplateManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadTemplateButtons();
    }

    bindEvents() {
        // Event listener untuk generate PDF
        $(document).on('click', '.generate-pdf-btn', (e) => {
            const pengajuanId = $(e.target).data('pengajuan-id');
            this.generatePDF(pengajuanId);
        });

        // Event listener untuk preview surat
        $(document).on('click', '.preview-surat-btn', (e) => {
            const pengajuanId = $(e.target).data('pengajuan-id');
            this.previewSurat(pengajuanId);
        });

        // Event listener untuk bulk generate
        $(document).on('click', '#bulk-generate-btn', () => {
            this.bulkGeneratePDF();
        });
    }

    // Load template buttons untuk setiap pengajuan
    loadTemplateButtons() {
        $('.pengajuan-row').each((index, row) => {
            const $row = $(row);
            const pengajuanId = $row.data('pengajuan-id');
            const jenisId = $row.data('jenis-id');
            const status = $row.data('status');

            // Only show buttons for approved letters
            if (status === 'disetujui') {
                this.checkTemplateAvailability(pengajuanId, jenisId, $row);
            }
        });
    }

    // Check apakah jenis surat memiliki template
    async checkTemplateAvailability(pengajuanId, jenisId, $row) {
        try {
            const response = await $.get(`/api/template/by-jenis/${jenisId}`);

            if (response.length > 0) {
                this.addTemplateButtons(pengajuanId, $row);
            }
        } catch (error) {
            console.error('Error checking template availability:', error);
        }
    }

    // Add template buttons ke row pengajuan
    addTemplateButtons(pengajuanId, $row) {
        const buttonsHtml = `
            <div class="template-actions">
                <button class="btn btn-sm btn-info preview-surat-btn"
                        data-pengajuan-id="${pengajuanId}"
                        title="Preview Surat">
                    <i class="ti ti-eye"></i> Preview
                </button>
                <button class="btn btn-sm btn-success generate-pdf-btn"
                        data-pengajuan-id="${pengajuanId}"
                        title="Generate PDF">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </button>
                <button class="btn btn-sm btn-primary download-surat-btn"
                        data-pengajuan-id="${pengajuanId}"
                        title="Download Surat"
                        style="display: none;">
                    <i class="ti ti-download"></i> Download
                </button>
            </div>
        `;

        // Find action column dan tambahkan buttons
        const $actionCol = $row.find('.action-column');
        if ($actionCol.length) {
            $actionCol.append(buttonsHtml);
        }

        // Check if PDF already exists
        this.checkExistingPDF(pengajuanId, $row);
    }

    // Check apakah PDF sudah pernah digenerate
    async checkExistingPDF(pengajuanId, $row) {
        try {
            const response = await $.get(`/api/pengajuan/${pengajuanId}/file-status`);

            if (response.has_file) {
                $row.find('.download-surat-btn').show();
                $row.find('.generate-pdf-btn').html('<i class="ti ti-refresh"></i> Regenerate');
            }
        } catch (error) {
            console.error('Error checking PDF status:', error);
        }
    }

    // Generate PDF untuk pengajuan
    async generatePDF(pengajuanId) {
        const $button = $(`.generate-pdf-btn[data-pengajuan-id="${pengajuanId}"]`);
        const originalText = $button.html();

        $button.prop('disabled', true)
               .html('<i class="ti ti-loader ti-spin"></i> Generating...');

        try {
            const response = await $.post(`/generate-pdf/${pengajuanId}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            });

            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'PDF berhasil digenerate',
                    icon: 'success',
                    timer: 2000
                });

                // Show download button
                const $row = $(`.pengajuan-row[data-pengajuan-id="${pengajuanId}"]`);
                $row.find('.download-surat-btn').show();
                $button.html('<i class="ti ti-refresh"></i> Regenerate');

                // Add download functionality
                $row.find('.download-surat-btn').off('click').on('click', () => {
                    window.open(response.download_url, '_blank');
                });

            } else {
                throw new Error(response.message || 'Gagal generate PDF');
            }

        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: error.responseJSON?.message || 'Gagal generate PDF',
                icon: 'error'
            });
        } finally {
            $button.prop('disabled', false).html(originalText);
        }
    }

    // Preview surat sebelum generate PDF
    previewSurat(pengajuanId) {
        const url = `/preview-surat/${pengajuanId}`;

        // Open in new window
        const previewWindow = window.open(url, 'preview', 'width=800,height=600,scrollbars=yes');

        if (!previewWindow) {
            // Fallback jika popup blocked
            window.location.href = url;
        }
    }

    // Bulk generate PDF untuk multiple pengajuan
    async bulkGeneratePDF() {
        const selectedIds = [];
        $('.pengajuan-checkbox:checked').each((index, checkbox) => {
            const pengajuanId = $(checkbox).data('pengajuan-id');
            // Only include approved pengajuan with templates
            const $row = $(checkbox).closest('.pengajuan-row');
            if ($row.data('status') === 'disetujui' && $row.find('.template-actions').length > 0) {
                selectedIds.push(pengajuanId);
            }
        });

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Pilih minimal satu pengajuan yang sudah disetujui dan memiliki template',
                icon: 'warning'
            });
            return;
        }

        const $button = $('#bulk-generate-btn');
        const originalText = $button.html();

        $button.prop('disabled', true)
               .html('<i class="ti ti-loader ti-spin"></i> Processing...');

        try {
            const response = await $.post('/bulk-generate-pdf', {
                pengajuan_ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            });

            if (response.success) {
                let successCount = 0;
                let errorCount = 0;

                response.results.forEach(result => {
                    if (result.status === 'success') {
                        successCount++;
                        // Update UI for successful generation
                        const $row = $(`.pengajuan-row[data-pengajuan-id="${result.id}"]`);
                        $row.find('.download-surat-btn').show();
                    } else {
                        errorCount++;
                    }
                });

                Swal.fire({
                    title: 'Bulk Generate Selesai!',
                    html: `
                        <p>Berhasil: ${successCount} file</p>
                        ${errorCount > 0 ? `<p>Gagal: ${errorCount} file</p>` : ''}
                    `,
                    icon: successCount > 0 ? 'success' : 'error'
                });

                // Reload table atau update UI
                if (typeof table !== 'undefined') {
                    table.ajax.reload();
                }
            }

        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal melakukan bulk generate',
                icon: 'error'
            });
        } finally {
            $button.prop('disabled', false).html(originalText);
        }
    }
}

// CSS untuk template buttons
const templateStyles = `
<style>
.template-actions {
    display: flex;
    gap: 5px;
    margin-top: 5px;
    flex-wrap: wrap;
}

.template-actions .btn {
    flex: 1;
    min-width: 70px;
}

@media (max-width: 768px) {
    .template-actions {
        flex-direction: column;
    }

    .template-actions .btn {
        width: 100%;
        margin-bottom: 2px;
    }
}

.ti-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
`;

// Initialize template manager when document ready
$(document).ready(function() {
    // Add CSS
    $('head').append(templateStyles);

    // Initialize template manager
    const templateManager = new TemplateManager();

    // Add bulk generate button if doesn't exist
    if ($('#bulk-generate-btn').length === 0 && $('.pengajuan-checkbox').length > 0) {
        const bulkButtonHtml = `
            <button id="bulk-generate-btn" class="btn btn-success me-2">
                <i class="ti ti-file-type-pdf me-2"></i>Bulk Generate PDF
            </button>
        `;

        // Add to toolbar area
        $('.card-header, .table-toolbar').first().append(bulkButtonHtml);
    }
});

// Export untuk penggunaan di file lain
window.TemplateManager = TemplateManager;
