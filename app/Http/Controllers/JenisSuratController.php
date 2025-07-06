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
        $query = JenisSurat::withCount('fields');

        return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                    <a href="/jenis-surat-fields/' . $item->id . '" class="btn btn-sm btn-info" title="Kelola Fields">
                        <i class="ti ti-settings"></i> Fields
                    </a>
                    <button class="btn btn-sm btn-primary edit" data-id="' . $item->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger delete" data-id="' . $item->id . '">Delete</button>
                ';
            })
            ->addColumn('fields_count', function ($item) {
                return $item->fields_count . ' fields';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $data = JenisSurat::updateOrCreate(
            ['id' => $request->id],
            ['nama' => $request->nama, 'deskripsi' => $request->deskripsi]
        );

        return response()->json(['success' => 'Data berhasil disimpan', 'data' => $data]);
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
