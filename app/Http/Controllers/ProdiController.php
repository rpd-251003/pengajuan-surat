<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    public function index()
    {
        return view('prodi.index');
    }

    // DataTable AJAX data
    public function data()
    {
        $query = Prodi::with('fakultas')->select('prodis.*');

        return DataTables::of($query)
            ->addColumn('fakultas', fn($row) => $row->fakultas ? $row->fakultas->nama : '-')
            ->addColumn('action', function($row) {
                return '
                <button data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit">Edit</button>
                <button data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $fakultas = Fakultas::all();
        return view('prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        Prodi::create($request->only('nama', 'fakultas_id'));

        return response()->json(['success' => true, 'message' => 'Prodi berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        $fakultas = Fakultas::all();

        return response()->json(['prodi' => $prodi, 'fakultas' => $fakultas]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->only('nama', 'fakultas_id'));

        return response()->json(['success' => true, 'message' => 'Prodi berhasil diupdate']);
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return response()->json(['success' => true, 'message' => 'Prodi berhasil dihapus']);
    }
}
