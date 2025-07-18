<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisSuratController extends Controller
{
    public function index()
    {
        return view('jenis_surat.index');
    }

    public function getData(Request $request)
    {
        $query = JenisSurat::with('fields');

        return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <a href="/jenis-surat-fields/' . $item->id . '" class="btn btn-sm btn-info" title="Kelola Fields">
                        <i class="ti ti-settings"></i> Fields
                    </a>
                    <button class="btn btn-sm btn-primary edit" data-id="' . $item->id . '" title="Edit Jenis Surat">
                        <i class="ti ti-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-warning edit-workflow" data-id="' . $item->id . '" title="Edit Workflow">
                        <i class="ti ti-workflow"></i> Workflow
                    </button>
                    <button class="btn btn-sm btn-danger delete" data-id="' . $item->id . '" title="Delete">
                        <i class="ti ti-trash"></i> Delete
                    </button>
                ';
            })
            ->addColumn('fields_list', function ($item) {
                $fields = $item->fields;
                if ($fields->isEmpty()) {
                    return '<span class="text-muted">Tidak ada fields</span>';
                }
                
                $fieldsList = [];
                foreach ($fields as $field) {
                    $typeIcon = [
                        'text' => 'ti-text',
                        'number' => 'ti-123',
                        'email' => 'ti-mail',
                        'date' => 'ti-calendar',
                        'file' => 'ti-file',
                        'textarea' => 'ti-align-left',
                        'select' => 'ti-list',
                        'checkbox' => 'ti-checkbox',
                        'radio' => 'ti-circle-dot'
                    ];
                    
                    $icon = $typeIcon[$field->field_type] ?? 'ti-point';
                    $required = $field->is_required ? '<span class="text-danger">*</span>' : '';
                    
                    $fieldsList[] = '<div class="d-flex align-items-center mb-1">
                        <i class="ti ' . $icon . ' me-2"></i>
                        <span class="field-name">' . $field->field_label . '</span>
                        ' . $required . '
                        <small class="text-muted ms-2">(' . $field->field_type . ')</small>
                    </div>';
                }
                
                return '<div class="fields-container">' . implode('', $fieldsList) . '</div>';
            })
            ->addColumn('approval_flow', function ($item) {
                $flow = $item->getApprovalFlow();
                $flowNames = [];
                foreach ($flow as $step) {
                    $stepNames = [
                        'dosen_pa' => 'Dosen PA',
                        'kaprodi' => 'Kaprodi',
                        'wadek1' => 'Wadek 1',
                        'tu' => 'TU',
                        'bak' => 'BAK'
                    ];
                    $flowNames[] = '<span class="badge bg-primary me-1">' . ($stepNames[$step] ?? $step) . '</span>';
                }
                return '<div class="approval-flow">' . implode(' → ', $flowNames) . '</div>';
            })
            ->addColumn('requires_number', function ($item) {
                return $item->requires_number_generation ?
                    '<span class="badge bg-success">Ya</span>' :
                    '<span class="badge bg-secondary">Tidak</span>';
            })
            ->rawColumns(['action', 'fields_list', 'approval_flow', 'requires_number'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // Check if this is a workflow update (has approval_flow but no deskripsi)
        if ($request->has('approval_flow') && !$request->has('deskripsi')) {
            return $this->updateWorkflow($request);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'approval_flow' => 'nullable|array',
            'approval_flow.*' => 'string|in:dosen_pa,kaprodi,wadek1,tu,bak',
            'requires_number_generation' => 'nullable|boolean'
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'approval_flow' => $request->approval_flow,
            'requires_number_generation' => $request->boolean('requires_number_generation')
        ];

        $jenisSurat = JenisSurat::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return response()->json(['success' => 'Data berhasil disimpan', 'data' => $jenisSurat]);
    }

    public function updateWorkflow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jenis_surats,id',
            'approval_flow' => 'required|array|min:1',
            'approval_flow.*' => 'string|in:dosen_pa,kaprodi,wadek1,tu,bak',
            'requires_number_generation' => 'nullable|boolean'
        ]);

        $jenisSurat = JenisSurat::findOrFail($request->id);
        $jenisSurat->update([
            'approval_flow' => $request->approval_flow,
            'requires_number_generation' => $request->boolean('requires_number_generation')
        ]);

        return response()->json(['success' => 'Workflow berhasil disimpan', 'data' => $jenisSurat]);
    }

    public function show($id)
    {
        return response()->json(JenisSurat::findOrFail($id));
    }

    public function destroy($id)
    {
        JenisSurat::findOrFail($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}
