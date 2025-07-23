<?php

namespace App\Http\Controllers;

use App\Models\SuratTemplate;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class SuratTemplateController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::all();
        return view('template.index', compact('jenisSurats'));
    }

    public function getData(Request $request)
    {
        $query = SuratTemplate::with('jenisSurat');

        return DataTables::of($query)
            ->addColumn('jenis_surat_nama', function ($item) {
                return $item->jenisSurat->nama;
            })
            ->addColumn('status', function ($item) {
                return $item->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <button class="btn btn-sm btn-info preview" data-id="' . $item->id . '" title="Preview">
                        <i class="ti ti-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-primary edit" data-id="' . $item->id . '" title="Edit">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-success toggle-status" data-id="' . $item->id . '" title="Toggle Status">
                        <i class="ti ti-toggle-' . ($item->is_active ? 'right' : 'left') . '"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete" data-id="' . $item->id . '" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenisSurats = JenisSurat::with('fields')->get();
        return view('template.create', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'nama_template' => 'required|string|max:255',
            'template_content' => 'required|string',
            'css_styles' => 'nullable|string',
            'variables' => 'nullable|array',
            'orientation' => 'required|in:portrait,landscape',
            'paper_size' => 'required|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Handle header image upload
        if ($request->hasFile('header_image')) {
            $data['header_image'] = $request->file('header_image')->store('template-headers', 'public');
        }

        // If this template is set as active, deactivate others
        if ($request->is_active) {
            SuratTemplate::where('jenis_surat_id', $request->jenis_surat_id)
                         ->update(['is_active' => false]);
        }

        SuratTemplate::create($data);

        return redirect()->route('template.index')->with('success', 'Template berhasil dibuat');
    }

    public function show($id)
    {
        $template = SuratTemplate::with('jenisSurat')->findOrFail($id);
        return response()->json($template);
    }

    public function edit($id)
    {
        $template = SuratTemplate::findOrFail($id);
        $jenisSurats = JenisSurat::with('fields')->get();
        return view('template.edit', compact('template', 'jenisSurats'));
    }

    public function update(Request $request, $id)
    {
        $template = SuratTemplate::findOrFail($id);

        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'nama_template' => 'required|string|max:255',
            'template_content' => 'required|string',
            'css_styles' => 'nullable|string',
            'variables' => 'nullable|array',
            'orientation' => 'required|in:portrait,landscape',
            'paper_size' => 'required|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Handle header image upload
        if ($request->hasFile('header_image')) {
            // Delete old image
            if ($template->header_image) {
                Storage::disk('public')->delete($template->header_image);
            }
            $data['header_image'] = $request->file('header_image')->store('template-headers', 'public');
        }

        // If this template is set as active, deactivate others
        if ($request->is_active) {
            SuratTemplate::where('jenis_surat_id', $request->jenis_surat_id)
                         ->where('id', '!=', $id)
                         ->update(['is_active' => false]);
        }

        $template->update($data);

        return redirect()->route('template.index')->with('success', 'Template berhasil diupdate');
    }

    public function destroy($id)
    {
        $template = SuratTemplate::findOrFail($id);

        // Delete header image if exists
        if ($template->header_image) {
            Storage::disk('public')->delete($template->header_image);
        }

        $template->delete();
        return response()->json(['success' => 'Template berhasil dihapus']);
    }

    public function toggleStatus($id)
    {
        $template = SuratTemplate::findOrFail($id);

        if (!$template->is_active) {
            // Deactivate other templates for this jenis surat
            SuratTemplate::where('jenis_surat_id', $template->jenis_surat_id)
                         ->update(['is_active' => false]);
            $template->update(['is_active' => true]);
        } else {
            $template->update(['is_active' => false]);
        }

        return response()->json(['success' => 'Status template berhasil diubah']);
    }

    public function preview($id)
    {
        $template = SuratTemplate::with(['jenisSurat', 'jenisSurat.fields'])->findOrFail($id);

        // Generate sample data for preview
        $sampleData = [];
        foreach ($template->jenisSurat->fields as $field) {
            $sampleData[$field->field_name] = $this->generateSampleValue($field);
        }

        // Add common variables
        $sampleData['tanggal_surat'] = now()->format('d F Y');
        $sampleData['nomor_surat'] = '001/ABC/VII/2025';

        $content = $template->generateContent($sampleData);

        return view('template.preview', compact('template', 'content'));
    }

    private function generateSampleValue($field)
    {
        switch ($field->field_type) {
            case 'text':
                return 'Sample ' . $field->field_label;
            case 'email':
                return 'sample@example.com';
            case 'number':
                return '12345';
            case 'select':
            case 'radio':
                $options = $field->field_options;
                if (is_array($options) && !empty($options)) {
                    // Get first value from associative array
                    return array_values($options)[0];
                }
                return 'Sample Option';
            case 'checkbox':
                $options = $field->field_options;
                if (is_array($options) && !empty($options)) {
                    // For checkbox, return first option value (checked)
                    return array_values($options)[0];
                }
                return 'Yes';
            case 'textarea':
                return 'Ini adalah contoh teks panjang untuk field ' . $field->field_label;
            case 'date':
                return now()->format('Y-m-d');
            case 'file':
                return 'sample-file.pdf';
            default:
                return 'Sample Value';
        }
    }

    public function getTemplatesByJenis($jenisId)
    {
        $templates = SuratTemplate::where('jenis_surat_id', $jenisId)
                                 ->where('is_active', true)
                                 ->get();
        return response()->json($templates);
    }
}
