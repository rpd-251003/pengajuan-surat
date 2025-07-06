<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\JenisSuratField;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisSuratFieldController extends Controller
{
    public function index($jenisSuratId, Request $request)
    {
        $jenisSurat = JenisSurat::findOrFail($jenisSuratId);

        if ($request->ajax()) {
            $query = JenisSuratField::where('jenis_surat_id', $jenisSuratId)->orderBy('sort_order');

            return DataTables::of($query)
                ->addColumn('action', function ($field) {
                    return '
                        <button class="btn btn-sm btn-primary edit" data-id="' . $field->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="' . $field->id . '">Delete</button>
                    ';
                })
                ->addColumn('required_badge', function ($field) {
                    return $field->is_required
                        ? '<span class="badge bg-danger">Required</span>'
                        : '<span class="badge bg-secondary">Optional</span>';
                })
                ->rawColumns(['action', 'required_badge'])
                ->make(true);
        }

        return view('jenis_surat_fields.index', compact('jenisSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,textarea,number,select,checkbox,radio,file',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        // Process field_options
        $fieldOptions = null;
        if (in_array($request->field_type, ['select', 'checkbox', 'radio']) && $request->field_options) {
            $options = [];
            foreach (explode("\n", $request->field_options) as $option) {
                $option = trim($option);
                if ($option) {
                    $parts = explode(':', $option, 2);
                    if (count($parts) === 2) {
                        $options[trim($parts[0])] = trim($parts[1]);
                    } else {
                        $options[$option] = $option;
                    }
                }
            }
            $fieldOptions = $options;
        }

        // Process validation rules
        $validationRules = [];
        if ($request->validation_rules) {
            $validationRules = explode('|', $request->validation_rules);
        }

        JenisSuratField::updateOrCreate(
            ['id' => $request->id],
            [
                'jenis_surat_id' => $request->jenis_surat_id,
                'field_name' => $request->field_name,
                'field_label' => $request->field_label,
                'field_type' => $request->field_type,
                'field_options' => $fieldOptions,
                'is_required' => $request->boolean('is_required'),
                'placeholder' => $request->placeholder,
                'validation_rules' => $validationRules,
                'sort_order' => $request->sort_order ?? 0
            ]
        );

        return response()->json(['success' => 'Field berhasil disimpan']);
    }

    public function show($id)
    {
        $field = JenisSuratField::findOrFail($id);

        // Format field_options untuk ditampilkan di form
        $fieldOptionsText = '';
        if ($field->field_options) {
            foreach ($field->field_options as $key => $value) {
                $fieldOptionsText .= "$key:$value\n";
            }
        }

        return response()->json([
            'id' => $field->id,
            'jenis_surat_id' => $field->jenis_surat_id,
            'field_name' => $field->field_name,
            'field_label' => $field->field_label,
            'field_type' => $field->field_type,
            'field_options' => trim($fieldOptionsText),
            'is_required' => $field->is_required,
            'placeholder' => $field->placeholder,
            'validation_rules' => is_array($field->validation_rules) ? implode('|', $field->validation_rules) : '',
            'sort_order' => $field->sort_order
        ]);
    }

    public function destroy($id)
    {
        JenisSuratField::findOrFail($id)->delete();
        return response()->json(['success' => 'Field berhasil dihapus']);
    }

    /**
     * Get fields for specific jenis surat (for AJAX)
     */
    public function getFields($jenisSuratId)
    {
        $fields = JenisSuratField::getFieldsByJenisSurat($jenisSuratId);
        return response()->json($fields);
    }
}
