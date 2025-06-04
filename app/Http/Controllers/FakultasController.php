<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FakultasController extends Controller
{
    public function index()
    {
        return view('fakultas.index');
    }

    // DataTables AJAX data source
    public function getData(Request $request)
    {
        $query = Fakultas::query();

        return DataTables::of($query)
            ->addColumn('action', function ($fakultas) {
                return '
                    <button class="btn btn-sm btn-primary edit" data-id="' . $fakultas->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger delete" data-id="' . $fakultas->id . '">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Store / Create
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $fakultas = Fakultas::updateOrCreate(
            ['id' => $request->id],
            ['nama' => $request->nama]
        );

        return response()->json(['success' => 'Fakultas saved successfully.', 'data' => $fakultas]);
    }

    // Show (for edit)
    public function show($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        return response()->json($fakultas);
    }

    // Delete
    public function destroy($id)
    {
        Fakultas::findOrFail($id)->delete();
        return response()->json(['success' => 'Fakultas deleted successfully.']);
    }
}
