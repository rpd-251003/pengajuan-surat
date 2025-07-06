<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KaprodiTahunan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class KaprodiTahunanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KaprodiTahunan::with(['prodi', 'user'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('prodi', function ($row) {
                    return $row->prodi->nama ?? '-';
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-primary edit" data-id="' . $row->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger delete" data-id="' . $row->id . '">Delete</button>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $prodis = Prodi::all();
        $users = User::where('role', '!=', 'mahasiswa')->get();

        return view('kaprodi_tahunan.index', compact('prodis', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_angkatan' => 'required',
            'prodi_id' => 'required',
            'user_id' => 'required',
        ]);

        KaprodiTahunan::updateOrCreate(
            ['id' => $request->id],
            $request->only('tahun_angkatan', 'prodi_id', 'user_id')
        );

        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = KaprodiTahunan::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        KaprodiTahunan::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
