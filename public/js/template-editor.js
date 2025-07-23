
        let tinymceEditor;

        $(document).ready(function() {
            // Initialize TinyMCE
            initTinyMCE();

            // Handle jenis surat change
            $('#jenis_surat_id').change(function() {
                loadDynamicVariables();
            });

            // Variable badge click handler
            $(document).on('click', '.variable-badge', function() {
                const variable = $(this).data('variable');
                if (variable && tinymceEditor) {
                    const placeholder = '{' + '{' + variable + '}' + '}';
                    tinymceEditor.insertContent(placeholder);
                }
            });

            // Preview button
            $('#previewBtn').click(function() {
                generatePreview();
            });

            // Print preview
            $('#printPreview').click(function() {
                printPreview();
            });
        });

        function initTinyMCE() {
            tinymce.init({
                selector: '#template_content',
                height: 600,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
                    'print', 'pagebreak'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | removeformat | help | code | preview | print',
                content_style: `
            body {
                font-family: Times, 'Times New Roman', serif;
                font-size: 12px;
                line-height: 1.6;
                margin: 40px;
            }
            table { border-collapse: collapse; width: 100%; }
            table td, table th { border: 1px solid #ddd; padding: 8px; }
            .header { text-align: center; margin-bottom: 30px; }
            .signature { margin-top: 50px; text-align: right; }
        `,
                setup: function(editor) {
                    tinymceEditor = editor;

                    // Add custom button for variables
                    editor.ui.registry.addMenuButton('variables', {
                        text: 'Variabel',
                        fetch: function(callback) {
                            var items = [{
                                    type: 'menuitem',
                                    text: 'Tanggal Surat',
                                    onAction: function() {
                                        editor.insertContent('{' + '{tanggal_surat}' + '}');
                                    }
                                },
                                {
                                    type: 'menuitem',
                                    text: 'Nomor Surat',
                                    onAction: function() {
                                        editor.insertContent('{' + '{nomor_surat}' + '}');
                                    }
                                },
                                {
                                    type: 'menuitem',
                                    text: 'Nama Mahasiswa',
                                    onAction: function() {
                                        editor.insertContent('{' + '{nama_mahasiswa}' +
                                            '}');
                                    }
                                },
                                {
                                    type: 'menuitem',
                                    text: 'NIM',
                                    onAction: function() {
                                        editor.insertContent('{' + '{nim}' + '}');
                                    }
                                }
                            ];
                            callback(items);
                        }
                    });
                },
                toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | variables | removeformat | help | code | preview | print'
            });
        }

        function loadDynamicVariables() {
            const selectedOption = $('#jenis_surat_id').find('option:selected');
            const fields = selectedOption.data('fields');

            let dynamicHtml = '';

            if (fields && fields.length > 0) {
                dynamicHtml += '<div class="mt-3 mb-2"><strong>Variabel Dinamis:</strong></div>';
                dynamicHtml += '<div class="variable-list">';

                fields.forEach(function(field) {
                    dynamicHtml += '<span class="badge bg-success me-1 mb-1 variable-badge" data-variable="' + field
                        .field_name + '">' + field.field_name + '</span>';
                });

                dynamicHtml += '</div>';
            }

            $('#dynamicVariables').html(dynamicHtml);
        }

        function loadTemplate(type) {
            let content = '';

            switch (type) {
                case 'keterangan':
                    content = `
                <div class="header">
                    <h2>UNIVERSITAS XYZ</h2>
                    <h3>{{ fakultas }}</h3>
                    <p>Jl. Pendidikan No. 123, Kota ABC 12345<br>
                    Telp: (021) 1234567, Email: info@univ-xyz.ac.id</p>
                    <hr>
                </div>

                <div class="content">
                    <h3 style="text-align: center;">SURAT KETERANGAN</h3>
                    <p style="text-align: center;">Nomor: {{ nomor_surat }}</p>

                    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

                    <table style="margin: 20px 0;">
                        <tr><td width="150">Nama</td><td width="20">:</td><td>{{ nama_mahasiswa }}</td></tr>
                        <tr><td>NIM</td><td>:</td><td>{{ nim }}</td></tr>
                        <tr><td>Program Studi</td><td>:</td><td>{{ prodi }}</td></tr>
                        <tr><td>Fakultas</td><td>:</td><td>{{ fakultas }}</td></tr>
                    </table>

                    <p>Adalah benar mahasiswa aktif dan terdaftar di {{ fakultas }}.</p>

                    <div class="signature">
                        <p>{{ tanggal_surat }}<br>
                        Ketua Program Studi<br><br><br>
                        [Nama Pejabat]</p>
                    </div>
                </div>
            `;
                    break;

                case 'pengantar':
                    content = `
                <div class="header">
                    <h2>UNIVERSITAS XYZ</h2>
                    <h3>{{ fakultas }}</h3>
                    <hr>
                </div>

                <div class="content">
                    <table style="margin-bottom: 30px;">
                        <tr><td width="15%">Nomor</td><td width="2%">:</td><td>{{ nomor_surat }}</td></tr>
                        <tr><td>Hal</td><td>:</td><td>Surat Pengantar</td></tr>
                    </table>

                    <p>Kepada Yth,<br>
                    [Tujuan Surat]<br>
                    Di tempat</p>

                    <p>Dengan hormat,</p>
                    <p>Mohon bantuan Bapak/Ibu untuk dapat menerima mahasiswa kami:</p>

                    <table style="margin: 20px 0;">
                        <tr><td width="150">Nama</td><td width="20">:</td><td>{{ nama_mahasiswa }}</td></tr>
                        <tr><td>NIM</td><td>:</td><td>{{ nim }}</td></tr>
                        <tr><td>Program Studi</td><td>:</td><td>{{ prodi }}</td></tr>
                    </table>

                    <p>Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

                    <div class="signature">
                        <p>{{ tanggal_surat }}<br>
                        Hormat kami,<br><br><br>
                        [Nama Pejabat]</p>
                    </div>
                </div>
            `;
                    break;

                case 'rekomendasi':
                    content = `
                <div class="header">
                    <h2>UNIVERSITAS XYZ</h2>
                    <h3>{{ fakultas }}</h3>
                    <hr>
                </div>

                <div class="content">
                    <h3 style="text-align: center;">SURAT REKOMENDASI</h3>
                    <p style="text-align: center;">Nomor: {{ nomor_surat }}</p>

                    <p>Yang bertanda tangan di bawah ini merekomendasikan mahasiswa:</p>

                    <table style="margin: 20px 0;">
                        <tr><td width="150">Nama</td><td width="20">:</td><td>{{ nama_mahasiswa }}</td></tr>
                        <tr><td>NIM</td><td>:</td><td>{{ nim }}</td></tr>
                        <tr><td>Program Studi</td><td>:</td><td>{{ prodi }}</td></tr>
                        <tr><td>IPK</td><td>:</td><td>[IPK Mahasiswa]</td></tr>
                    </table>

                    <p>Mahasiswa yang bersangkutan memiliki prestasi akademik yang baik dan layak untuk direkomendasikan.</p>

                    <div class="signature">
                        <p>{{ tanggal_surat }}<br>
                        Pemberi Rekomendasi,<br><br><br>
                        [Nama Dosen]</p>
                    </div>
                </div>
            `;
                    break;
            }

            if (tinymceEditor && content) {
                tinymceEditor.setContent(content);
            }
        }

        function generatePreview() {
            if (!tinymceEditor) return;

            const templateContent = tinymceEditor.getContent();
            const cssStyles = $('#css_styles').val();

            if (!templateContent.trim()) {
                alert('Konten template tidak boleh kosong');
                return;
            }

            // Generate sample data
            const sampleData = {
                'tanggal_surat': new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }),
                'nomor_surat': '001/ABC/VII/2025',
                'nama_mahasiswa': 'John Doe',
                'nim': '2021001001',
                'prodi': 'Teknik Informatika',
                'fakultas': 'Fakultas Teknik',
                'tahun_angkatan': '2021'
            };

            // Add dynamic fields sample data
            const selectedOption = $('#jenis_surat_id').find('option:selected');
            const fields = selectedOption.data('fields');

            if (fields) {
                fields.forEach(function(field) {
                    sampleData[field.field_name] = getSampleValue(field);
                });
            }

            // Replace placeholders
            let previewContent = templateContent;
            Object.keys(sampleData).forEach(function(key) {
                const regex = new RegExp('\\{\\{' + key + '\\}\\}', 'g');
                previewContent = previewContent.replace(regex, sampleData[key]);
            });

            // Add CSS if exists
            if (cssStyles.trim()) {
                previewContent = '<style>' + cssStyles + '</style>' + previewContent;
            }

            $('#previewContent').html(previewContent);
            $('#previewModal').modal('show');
        }

        function getSampleValue(field) {
    if (!field || !field.field_type) return 'Invalid Field';

    switch (field.field_type) {
        case 'text':
            return 'Sample ' + (field.field_label || 'Text');
        case 'email':
            return 'sample@example.com';
        case 'number':
            return '12345';
        case 'select':
            if (field.field_options && field.field_options.length > 0) {
                const firstOption = field.field_options[0];
                return typeof firstOption === 'string'
                    ? firstOption
                    : firstOption?.value || 'Sample Option';
            }
            return 'Sample Option';
        case 'textarea':
            return 'Ini adalah contoh teks panjang untuk field ' + (field.field_label || '');
        case 'date':
            return new Date().toISOString().split('T')[0];
        default:
            return 'Sample Value';
    }
}


        function printPreview() {
            const printContent = document.getElementById('previewContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

