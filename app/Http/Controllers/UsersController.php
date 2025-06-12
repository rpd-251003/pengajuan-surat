<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'role', 'nomor_identifikasi', 'created_at']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info me-1" onclick="showUser('.$row->id.')"><i class="ti ti-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning me-1" onclick="editUser('.$row->id.')"><i class="ti ti-edit"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser('.$row->id.')"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('role_badge', function($row){
                    $badges = [
                        'admin' => 'bg-primary',
                        'mahasiswa' => 'bg-success',
                        'dosen' => 'bg-info',
                        'kaprodi' => 'bg-warning',
                        'wadek1' => 'bg-secondary',
                        'tu' => 'bg-dark'
                    ];
                    $class = $badges[$row->role] ?? 'bg-secondary';
                    return '<span class="badge '.$class.'">'.ucfirst($row->role).'</span>';
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action', 'role_badge'])
                ->make(true);
        }

        return view('tu.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'html' => view('tu.users.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa,dosen,kaprodi,wadek1,tu',
            'nomor_identifikasi' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nomor_identifikasi' => $request->nomor_identifikasi,
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'html' => view('tu.users.show', compact('user'))->render()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'html' => view('tu.users.edit', compact('user'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa,dosen,kaprodi,wadek1,tu',
            'nomor_identifikasi' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'nomor_identifikasi' => $request->nomor_identifikasi,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting current user
            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri!'
                ]);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
