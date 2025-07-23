<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Template - {{ $template->nama_template }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .preview-container {
            background: white;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 297mm;
        }
        
        .template-info {
            background: #e3f2fd;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
        
        .template-info h4 {
            margin: 0 0 5px 0;
            color: #1976D2;
        }
        
        .template-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        
        .content-wrapper {
            {{ $template->css_styles ?? '' }}
        }
        
        /* Default styling for common elements */
        .content-wrapper h1, .content-wrapper h2, .content-wrapper h3 {
            color: #333;
            margin-top: 0;
        }
        
        .content-wrapper p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        .content-wrapper table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .content-wrapper table td, .content-wrapper table th {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        .content-wrapper table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .template-info {
                display: none;
            }
            
            .preview-container {
                box-shadow: none;
                margin: 0;
                padding: 20mm;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="template-info">
            <h4>{{ $template->nama_template }}</h4>
            <p>
                <strong>Jenis Surat:</strong> {{ $template->jenisSurat->nama }} | 
                <strong>Orientasi:</strong> {{ ucfirst($template->orientation) }} | 
                <strong>Ukuran:</strong> {{ $template->paper_size }}
            </p>
            <p><em>* Data di bawah ini adalah contoh data untuk preview template</em></p>
        </div>
        
        <div class="content-wrapper">
            @if($template->header_image)
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ asset('storage/' . $template->header_image) }}" 
                         alt="Header" 
                         style="max-width: 100%; height: auto;">
                </div>
            @endif
            
            {!! $content !!}
            
            @if($template->footer_text)
                <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
                    {!! $template->footer_text !!}
                </div>
            @endif
        </div>
    </div>
</body>
</html>