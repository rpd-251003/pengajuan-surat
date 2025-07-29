<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\SuratTemplate;
use App\Models\FileApproval;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

class PDFGeneratorController extends Controller
{
    public function generateSuratPDF($pengajuanId)
    {
        $pengajuan = PengajuanSurat::with([
            'mahasiswa',
            'jenisSurat.activeTemplate',
            'details',
            'mahasiswa.user'
        ])->findOrFail($pengajuanId);

        // Check if template exists
        if (!$pengajuan->jenisSurat->activeTemplate) {
            return response()->json([
                'error' => 'Template tidak ditemukan untuk jenis surat ini'
            ], 404);
        }

        $template = $pengajuan->jenisSurat->activeTemplate;

        // Prepare data for template
        $data = $this->prepareTemplateData($pengajuan);

        // Generate PDF content
        $htmlContent = $template->generateContent($data);
        $fullTemplate = $template->getFullTemplate();

        // Replace content in template
        $finalContent = str_replace($template->template_content, $htmlContent, $fullTemplate);

        // Generate PDF
        $pdf = $this->createPDF($finalContent, $template);

        // Generate filename
        $filename = $this->generateFilename($pengajuan);

        // Save PDF to storage
        $pdfPath = 'surat-generated/' . $filename;
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Update or create file approval record
        FileApproval::updateOrCreate(
            ['id_pengajuan' => $pengajuanId],
            [
                'nomor_surat' => $data['nomor_surat'],
                'file_surat' => $pdfPath
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'PDF berhasil digenerate',
            'file_path' => $pdfPath,
            'download_url' => route('download.surat', $pengajuanId)
        ]);
    }

    public function downloadSurat($pengajuanId)
    {
        $pengajuan = PengajuanSurat::with([
            'mahasiswa',
            'jenisSurat.activeTemplate',
            'details',
            'mahasiswa.user'
        ])->findOrFail($pengajuanId);

        if (!$pengajuan->jenisSurat->activeTemplate) {
            abort(404, 'Template tidak ditemukan untuk jenis surat ini');
        }

        $template = $pengajuan->jenisSurat->activeTemplate;
        $data = $this->prepareTemplateData($pengajuan);
        $htmlContent = $template->generateContent($data);

        // Header statis dari Universitas Darma Persada (sama seperti preview)
        $staticHeader = '
        <style>
        @page {
            margin: 15mm;
        }
        body {
            transform: scale(0.8);
            transform-origin: top left;
            width: 125%;
            margin: 0;
            padding: 0;
        }
    </style>
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
    </div>';

        // Gabungkan header statis + template content
        $fullTemplate = $template->getFullTemplate();
        $finalContent = $staticHeader . str_replace($template->template_content, $htmlContent, $fullTemplate);

        // Generate PDF langsung tanpa menyimpan ke storage
        $pdf = $this->createPDF($finalContent, $template);

        // Generate filename
        $filename = $this->generateFilename($pengajuan);

        // Return PDF sebagai download langsung
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate')
            ->header('Pragma', 'public');
    }

    public function previewSurat($pengajuanId)
    {
        $pengajuan = PengajuanSurat::with([
            'mahasiswa',
            'jenisSurat.activeTemplate',
            'details',
            'mahasiswa.user'
        ])->findOrFail($pengajuanId);

        if (!$pengajuan->jenisSurat->activeTemplate) {
            return response()->json([
                'error' => 'Template tidak ditemukan untuk jenis surat ini'
            ], 404);
        }

        $template = $pengajuan->jenisSurat->activeTemplate;
        $data = $this->prepareTemplateData($pengajuan);
        $htmlContent = $template->generateContent($data);

        // Header statis dari Universitas Darma Persada
        $staticHeader = '
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
    </div>';

        // Gabungkan header statis + template content
        $fullTemplate = $template->getFullTemplate();
        $finalContent = $staticHeader . str_replace($template->template_content, $htmlContent, $fullTemplate);

        return response($finalContent)->header('Content-Type', 'text/html');
    }

    private function prepareTemplateData($pengajuan)
    {
        // Base data
        
        \Log::debug('Data pengajuan yang diterima:', [
        'fileApproval' => $pengajuan->fileApproval,
        'nomor_surat' => $pengajuan->fileApproval->nomor_surat ?? null,
        'mahasiswa_user' => optional($pengajuan->mahasiswa)->user,
        'nim' => optional($pengajuan->mahasiswa)->nim,
        'prodi' => $pengajuan->prodi,
        'fakultas' => $pengajuan->fakultas,
        'tahun_angkatan' => $pengajuan->tahun_angkatan,
        'jenis_surat' => $pengajuan->jenisSurat,
        'keterangan' => $pengajuan->keterangan,
    ]);


        $data = [
            'tanggal_surat' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->fileApproval->nomor_surat ?? 'Belum Ditentukan',
            'nama_mahasiswa' => $pengajuan->mahasiswa->user->name,
            'nim' => $pengajuan->mahasiswa->nim,
            'prodi' => $pengajuan->prodi->nama ?? 'Belum Ditentukan',
            'fakultas' => $pengajuan->fakultas->nama ?? 'Belum Ditentukan',
            'tahun_angkatan' => $pengajuan->tahun_angkatan,
            'nama_jenis_surat' => $pengajuan->jenisSurat->nama,
            'keterangan' => $pengajuan->keterangan,
        ];

        // Add dynamic field data
        foreach ($pengajuan->details as $detail) {
            $data[$detail->field_name] = $detail->field_value;
        }

        // Add approval data if available
        if ($pengajuan->approved_by_dosen_pa) {
            $dosenPa = \App\Models\User::find($pengajuan->approved_by_dosen_pa);
            $data['approved_by_dosen_pa'] = $dosenPa->name ?? '';
            $data['approved_at_dosen_pa'] = $pengajuan->approved_at_dosen_pa
                ? \Carbon\Carbon::parse($pengajuan->approved_at_dosen_pa)->format('d F Y')
                : '';
        }

        if ($pengajuan->approved_by_kaprodi) {
            $kaprodi = \App\Models\User::find($pengajuan->approved_by_kaprodi);
            $data['approved_by_kaprodi'] = $kaprodi->name ?? '';
            $data['approved_at_kaprodi'] = $pengajuan->approved_at_kaprodi
                ? \Carbon\Carbon::parse($pengajuan->approved_at_kaprodi)->format('d F Y')
                : '';
        }

        if ($pengajuan->approved_by_wadek1) {
            $wadek1 = \App\Models\User::find($pengajuan->approved_by_wadek1);
            $data['approved_by_wadek1'] = $wadek1->name ?? '';
            $data['approved_at_wadek1'] = $pengajuan->approved_at_wadek1
                ? \Carbon\Carbon::parse($pengajuan->approved_at_wadek1)->format('d F Y')
                : '';
        }

        if ($pengajuan->approved_by_staff_tu) {
            $staffTu = \App\Models\User::find($pengajuan->approved_by_staff_tu);
            $data['approved_by_staff_tu'] = $staffTu->name ?? '';
            $data['approved_at_staff_tu'] = $pengajuan->approved_at_staff_tu
                ? \Carbon\Carbon::parse($pengajuan->approved_at_staff_tu)->format('d F Y')
                : '';
        }

        return $data;
    }

    private function createPDF($htmlContent, $template)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        // Set paper size and orientation
        $dompdf->setPaper($template->paper_size, $template->orientation);

        $dompdf->render();

        return $dompdf;
    }

    private function generateNomorSurat($pengajuan)
    {
        // Generate nomor surat based on your format
        // Example: 001/SK/VII/2025
        $year = now()->year;
        $month = now()->format('m');
        $monthRoman = $this->numberToRoman($month);

        // Get last number for this month and year
        $lastFile = FileApproval::whereNotNull('nomor_surat')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastFile && $lastFile->nomor_surat) {
            // Extract number from last nomor_surat
            preg_match('/^(\d+)/', $lastFile->nomor_surat, $matches);
            if (isset($matches[1])) {
                $nextNumber = intval($matches[1]) + 1;
            }
        }

        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "{$formattedNumber}/SK/{$monthRoman}/{$year}";
    }

    private function generateFilename($pengajuan)
    {
        $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $pengajuan->mahasiswa->user->name));
        $cleanJenis = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $pengajuan->jenisSurat->nama));

        return "Surat-{$cleanJenis}-{$cleanName}-{$pengajuan->id}.pdf";
    }

    private function numberToRoman($number)
    {
        $map = [
            12 => 'XII',
            11 => 'XI',
            10 => 'X',
            9 => 'IX',
            8 => 'VIII',
            7 => 'VII',
            6 => 'VI',
            5 => 'V',
            4 => 'IV',
            3 => 'III',
            2 => 'II',
            1 => 'I'
        ];

        return $map[$number] ?? 'I';
    }

    public function bulkGenerate(Request $request)
    {
        $pengajuanIds = $request->input('pengajuan_ids', []);
        $results = [];

        foreach ($pengajuanIds as $id) {
            try {
                $result = $this->generateSuratPDF($id);
                $results[] = [
                    'id' => $id,
                    'status' => 'success',
                    'data' => $result->getData()
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'id' => $id,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
}
