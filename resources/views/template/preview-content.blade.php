<div
    style="background: #e3f2fd; padding: 15px; margin-bottom: 20px; border-left: 4px solid #2196F3; border-radius: 4px;">
    <h5 style="margin: 0 0 10px 0; color: #1976D2;">{{ $template->nama_template }}</h5>
    <p style="margin: 0; color: #666; font-size: 14px;">
        <strong>Jenis Surat:</strong> {{ $template->jenisSurat->nama }} |
        <strong>Orientasi:</strong> {{ ucfirst($template->orientation) }} |
        <strong>Ukuran:</strong> {{ $template->paper_size }}
    </p>
    <p style="margin: 5px 0 0 0; color: #666; font-size: 12px; font-style: italic;">
        * Data di bawah ini adalah contoh data untuk preview template
    </p>
</div>

<div
    style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px; font-family: Arial, sans-serif; line-height: 1.6;">
    <style>
        /* Apply template CSS styles */
        {{ $template->css_styles ?? '' }}

        /* Default preview styles */
        .preview-content {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .preview-content h1,
        .preview-content h2,
        .preview-content h3 {
            color: #333;
            margin-top: 0;
        }

        .preview-content p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .preview-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .preview-content table td,
        .preview-content table th {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .preview-content table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>

    <div class="preview-content">
        <div style="clear:both;">
        <p style="margin-top:6pt; margin-left:63.8pt; margin-bottom:0pt; text-align:center; line-height:normal; font-size:24pt;">
            <span style="height:0pt; margin-top:-6pt; text-align:left; display:block; position:absolute; z-index:-65537;">
                <img src="https://r-code.online/img/unsada-logo.png" width="120" height="120" alt="Logo" style="margin: 0 0 0 auto; display: block;">
            </span>
            <span style="height:0pt; margin-top:-6pt; text-align:left; display:block; position:absolute; z-index:-65534;">
                <img src="https://r-code.online/img/unsada-logo.png" width="120" height="120" alt="" style="margin: 0 0 0 auto; display: block;">
            </span>
            <strong><span style="font-family:\'Times New Roman\';">UNIVERSITAS DARMA PERSADA</span></strong>
        </p>
        <p style="margin-top:0pt; margin-left:63.8pt; margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt;">
            Jl. Taman Malaka Selatan Pondok Kelapa Jakarta 13450
        </p>
        <p style="margin-top:0pt; margin-left:63.8pt; margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt;">
            Telp. 021 – 8649051, 8649053, 8649057 Fax. (021) 8649052
        </p>
        <p style="margin-top:0pt; margin-left:63.8pt; margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt;">
            E-mail: humas@unsada.ac.id Home page: http://www.unsada.ac.id
        </p>
        <hr style="border: 1px solid #000; margin: 30px 0;">
    </div>

        {!! $content !!}

        @if ($template->footer_text)
            <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
                {!! $template->footer_text !!}
            </div>
        @endif
    </div>
</div>
