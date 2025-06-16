<?php

namespace App\Http\Controllers;

use App\Models\FileApproval;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Http\Controllers\Controller;

class FileApprovalController extends Controller
{

    public function data()
    {
        $query = FileApproval::with('pengajuan');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pengajuan', function ($row) {
                return $row->pengajuan->id ?? '-';
            })
            ->addColumn('file_surat', function ($row) {
                if ($row->file_surat) {
                    $url = asset('storage/' . $row->file_surat);
                    return '<a href="' . $url . '" target="_blank">Lihat</a>';
                }
                return '-';
            })
            ->addColumn('aksi', function ($row) {
                $deleteUrl = route('file-approvals.destroy', $row->id);
                return '
                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Yakin hapus?\')">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </form>';
            })
            ->rawColumns(['file_surat', 'aksi'])
            ->make(true);
    }


    public function index()
    {
        $fileApprovals = FileApproval::with('pengajuan')->latest()->paginate(5);
        $pengajuans = PengajuanSurat::all();
        return view('file_approvals.index', compact('fileApprovals', 'pengajuans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan_surats,id',
            'nomor_surat' => 'required',
            'file_surat' => 'required|file|mimes:pdf,doc,docx'
        ]);

        if ($request->hasFile('file_surat')) {
            $filename = $request->file('file_surat')->store('files', 'public');
            $validated['file_surat'] = $filename;
        }

        FileApproval::create($validated);
        return redirect()->back()->with('success', 'File berhasil disimpan.');
    }

    public function update(Request $request, FileApproval $fileApproval)
    {
        $validated = $request->validate([
            'id_pengajuan' => 'required|exists:pengajuan_surats,id',
            'nomor_surat' => 'required',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx'
        ]);

        if ($request->hasFile('file_surat')) {
            $filename = $request->file('file_surat')->store('files', 'public');
            $validated['file_surat'] = $filename;
        }

        $fileApproval->update($validated);
        return redirect()->back()->with('success', 'File berhasil diperbarui.');
    }

    public function destroy(FileApproval $fileApproval)
    {
        $fileApproval->delete();
        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }
}
