<div style="background: #e3f2fd; padding: 15px; margin-bottom: 20px; border-left: 4px solid #2196F3; border-radius: 4px;">
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

<div style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px; font-family: Arial, sans-serif; line-height: 1.6;">
    <style>
        /* Apply template CSS styles */
        {{ $template->css_styles ?? '' }}
        
        /* Default preview styles */
        .preview-content {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .preview-content h1, .preview-content h2, .preview-content h3 {
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
        
        .preview-content table td, .preview-content table th {
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
        <!-- Kop Surat Universitas Darma Persada -->
        {!! $staticHeader !!}
        
        @if($template->header_image)
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ asset('storage/' . $template->header_image) }}" 
                     alt="Header" 
                     style="max-width: 100%; height: auto; max-height: 150px;">
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