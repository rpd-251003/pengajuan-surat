<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('mahasiswa');
    }

    public function index_admins()
    {
        return view('dashboard');
    }

    public function index_admin()
    {
        // 1. Count total users dan mahasiswa
        $totalUsers = User::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();

        // 2. Count surat berdasarkan status
        $statusCounts = [
            'diajukan' => PengajuanSurat::where('status', 'diajukan')->count(),
            'diproses' => PengajuanSurat::where('status', 'diproses')->count(),
            'disetujui' => PengajuanSurat::where('status', 'disetujui')->count(),
            'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
        ];

        // 3. Count surat yang belum di-approve per role
        $pendingApprovals = [
            'dosen_pa' => PengajuanSurat::whereNull('approved_at_dosen_pa')->count(),
            'kaprodi' => PengajuanSurat::whereNull('approved_at_kaprodi')->count(),
            'wadek1' => PengajuanSurat::whereNull('approved_at_wadek1')->count(),
            'staff_tu' => PengajuanSurat::whereNull('approved_at_staff_tu')->count(),
        ];

        // 4. List penanggung jawab untuk Dosen PA (dengan count pending)
        $dosenPAList = PengajuanSurat::select('approved_by_dosen_pa')
            ->with(['dosenPA:id,name'])
            ->whereNotNull('approved_by_dosen_pa')
            ->whereNull('approved_at_dosen_pa')
            ->groupBy('approved_by_dosen_pa')
            ->selectRaw('approved_by_dosen_pa, COUNT(*) as pending_count')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->approved_by_dosen_pa,
                    'name' => $item->dosenPA->name ?? 'Unknown',
                    'pending_count' => $item->pending_count
                ];
            });

        // 5. List penanggung jawab untuk Kaprodi (dengan count pending)
        $kaprodiList = PengajuanSurat::select('approved_by_kaprodi')
            ->with(['kaprodi:id,name'])
            ->whereNotNull('approved_by_kaprodi')
            ->whereNull('approved_at_kaprodi')
            ->groupBy('approved_by_kaprodi')
            ->selectRaw('approved_by_kaprodi, COUNT(*) as pending_count')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->approved_by_kaprodi,
                    'name' => $item->kaprodi->name ?? 'Unknown',
                    'pending_count' => $item->pending_count
                ];
            });

        // 6. Grafik surat yang dibuat per hari (30 hari terakhir)
        $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay();
        $today = Carbon::now()->endOfDay();

        // Generate semua tanggal dalam 30 hari terakhir
        $dateRange = [];
        for ($date = $thirtyDaysAgo->copy(); $date->lte($today); $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        // Ambil data surat yang dibuat dalam 30 hari terakhir
        $suratPerDay = PengajuanSurat::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$thirtyDaysAgo, $today])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date')
            ->toArray();

        // Gabungkan dengan tanggal kosong (count = 0)
        $chartData = [];
        foreach ($dateRange as $date) {
            $chartData[] = [
                'date' => $date,
                'count' => $suratPerDay[$date] ?? 0,
                'formatted_date' => Carbon::parse($date)->format('d M')
            ];
        }

        // 7. Total surat yang diajukan
        $totalSurat = PengajuanSurat::count();

        // 8. Surat terbaru (5 terakhir) untuk informasi tambahan
        $recentSurat = PengajuanSurat::with(['mahasiswa.user:id,name', 'jenisSurat:id,nama'])
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($surat) {
                return [
                    'id' => $surat->id,
                    'mahasiswa_name' => $surat->mahasiswa->user->name ?? 'Unknown',
                    'jenis_surat' => $surat->jenisSurat->nama ?? 'Unknown',
                    'status' => $surat->status,
                    'created_at' => $surat->created_at->format('d M Y H:i'),
                ];
            });

        // 9. Progress approval (berapa persen yang sudah complete)
        $totalWithApproval = PengajuanSurat::whereIn('status', ['diproses', 'disetujui', 'diajukan'])->count();

        $completeApproval = PengajuanSurat::whereIn('status', ['diproses', 'disetujui'])
            ->whereNotNull('approved_at_dosen_pa')
            ->whereNotNull('approved_at_kaprodi')
            ->whereNotNull('approved_at_wadek1')
            ->whereNotNull('approved_at_staff_tu')
            ->count();

        $approvalProgress = $totalWithApproval > 0 ? round(($completeApproval / $totalWithApproval) * 100, 1) : 0;


        return view('dashboard', compact(
            'totalUsers',
            'totalMahasiswa',
            'statusCounts',
            'pendingApprovals',
            'dosenPAList',
            'kaprodiList',
            'chartData',
            'totalSurat',
            'recentSurat',
            'approvalProgress'
        ));
    }

    // Method terpisah untuk mendapatkan data chart via AJAX (opsional)
    public function chartData(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        $suratPerDay = PengajuanSurat::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date')
            ->toArray();

        $chartData = [];
        foreach ($dateRange as $date) {
            $chartData[] = [
                'date' => $date,
                'count' => $suratPerDay[$date] ?? 0,
                'formatted_date' => Carbon::parse($date)->format('d M')
            ];
        }

        return response()->json($chartData);
    }

    // Method untuk mendapatkan detail pending approvals (opsional)
    public function pendingApprovalDetails($type)
    {
        switch ($type) {
            case 'dosen_pa':
                $data = PengajuanSurat::with(['mahasiswa.user:id,name', 'jenisSurat:id,nama', 'dosenPA:id,name'])
                    ->whereNull('approved_at_dosen_pa')
                    ->whereNotNull('approved_by_dosen_pa')
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'kaprodi':
                $data = PengajuanSurat::with(['mahasiswa.user:id,name', 'jenisSurat:id,nama', 'kaprodi:id,name'])
                    ->whereNull('approved_at_kaprodi')
                    ->whereNotNull('approved_by_kaprodi')
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'wadek1':
                $data = PengajuanSurat::with(['mahasiswa.user:id,name', 'jenisSurat:id,nama'])
                    ->whereNull('approved_at_wadek1')
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            case 'staff_tu':
                $data = PengajuanSurat::with(['mahasiswa.user:id,name', 'jenisSurat:id,nama'])
                    ->whereNull('approved_at_staff_tu')
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json($data);
    }

    // Method untuk statistik real-time (opsional, untuk refresh via AJAX)
    public function getStats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_surat' => PengajuanSurat::count(),
            'status_counts' => [
                'diajukan' => PengajuanSurat::where('status', 'diajukan')->count(),
                'diproses' => PengajuanSurat::where('status', 'diproses')->count(),
                'disetujui' => PengajuanSurat::where('status', 'disetujui')->count(),
                'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
            ],
            'pending_approvals' => [
                'dosen_pa' => PengajuanSurat::whereNull('approved_at_dosen_pa')->count(),
                'kaprodi' => PengajuanSurat::whereNull('approved_at_kaprodi')->count(),
                'wadek1' => PengajuanSurat::whereNull('approved_at_wadek1')->count(),
                'staff_tu' => PengajuanSurat::whereNull('approved_at_staff_tu')->count(),
            ]
        ]);
    }


    public function index_mahasiswa()
    {

        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        $latestPengajuan = null;

        if ($mahasiswa) {
            $latestPengajuan = PengajuanSurat::with([
                'jenisSurat',
                'dosenPA',
                'kaprodi',
                'wadek1',
                'staffTU'
            ])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('created_at', 'desc')
                ->first(); // Get only the latest one
        }

        return view('mahasiswa.dashboard.index', compact('latestPengajuan'));
    }
}
