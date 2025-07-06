<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;


class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mahasiswa::with(['user', 'fakultas', 'prodi'])->get();
            return DataTables::of($data)
                ->addColumn('user.name', fn($row) => $row->user->name ?? '-')
                ->addColumn('user.email', fn($row) => $row->user->email ?? '-')
                ->addColumn('user.nomor_identifikasi', fn($row) => $row->user->nomor_identifikasi ?? '-')
                ->addColumn('fakultas.nama', fn($row) => $row->fakultas->nama ?? '-')
                ->addColumn('prodi.nama', fn($row) => $row->prodi->nama ?? '-')
                ->make(true);
        }

        $fakultas = Fakultas::all();
        $prodis = Prodi::all();

        return view('mahasiswa.index', compact('fakultas', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'nomor_identifikasi' => 'required|unique:users,nomor_identifikasi,' . $request->user_id,
            'password' => $request->user_id ? '' : 'required|min:6',
            'fakultas_id' => 'required',
            'prodi_id' => 'required',
            'angkatan' => 'required|digits:4',
        ]);

        $user = User::updateOrCreate(
            ['id' => $request->user_id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'nomor_identifikasi' => $request->nomor_identifikasi,
                'password' => $request->password ? Hash::make($request->password) : User::find($request->user_id)->password,
                'role' => 'mahasiswa',
            ]
        );

        Mahasiswa::updateOrCreate(
            ['id' => $request->mahasiswa_id],
            [
                'user_id' => $user->id,
                'fakultas_id' => $request->fakultas_id,
                'prodi_id' => $request->prodi_id,
                'angkatan' => $request->angkatan,
            ]
        );

        return response()->json(['success' => 'Data mahasiswa berhasil disimpan']);
    }

    public function edit($id)
    {
        $data = Mahasiswa::with('user')->findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswa->user->delete();
        $mahasiswa->delete();

        return response()->json(['success' => 'Data mahasiswa berhasil dihapus']);
    }
}
