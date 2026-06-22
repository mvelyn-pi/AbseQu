<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Services\AttendanceService;
use App\Services\LeaderboardService;

class DashboardGuruController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService,
        private LeaderboardService $leaderboardService,
    ) {}

    public function index()
    {
        $user    = auth()->user();
        $summary = $this->attendanceService->getTodaySummary();

        // If guru is a wali kelas, show their class's summary
        $waliKelas = $user->kelasWali()->with('students')->first();

        $recentScans = \App\Models\Attendance::with('student.kelas')
            ->whereDate('tanggal', today())
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->orderByDesc('waktu_scan')
            ->limit(10)
            ->get();

        $pendingIzin = \App\Models\LeaveRequest::where('status', 'Pending')->count();

        return view('guru.dashboard', compact('summary', 'waliKelas', 'recentScans', 'pendingIzin'));
    }

    public function leaderboard()
    {
        $kelasList   = Kelas::orderBy('nama_kelas')->get();
        $leaderboard = $this->leaderboardService->getRankings();
        return view('guru.leaderboard', compact('kelasList', 'leaderboard'));
    }
}
