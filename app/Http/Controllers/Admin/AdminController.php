<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\LeaderboardService;

class AdminController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService,
        private LeaderboardService $leaderboardService,
    ) {}

    public function dashboard()
    {
        $summary = $this->attendanceService->getTodaySummary();

        // Merge stats including today attendance counts
        $stats = [
            'total_siswa'  => Student::where('aktif', true)->count(),
            'total_kelas'  => Kelas::count(),
            'total_guru'   => User::where('role', 'guru')->count(),
            'total_ortu'   => User::where('role', 'orangtua')->count(),
            'hadir'        => $summary['hadir'] ?? 0,
            'terlambat'    => $summary['terlambat'] ?? 0,
            'alpha'        => $summary['alpha'] ?? 0,
        ];

        // Today's attendance records (latest 20)
        $todayAttendances = Attendance::with(['student.kelas'])
            ->whereDate('tanggal', today())
            ->orderByDesc('waktu_scan')
            ->take(20)
            ->get();

        // Last 7 days attendance trend
        $trend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            $hadir = Attendance::whereDate('tanggal', $date)
                ->whereIn('status', ['Hadir', 'Terlambat'])->count();
            $alpha = Attendance::whereDate('tanggal', $date)->where('status', 'Alpha')->count();
            return [
                'date'  => $date->format('d M'),
                'hadir' => $hadir,
                'alpha' => $alpha,
            ];
        });

        $pendingIzin = \App\Models\LeaveRequest::where('status', 'Pending')->count();

        return view('admin.dashboard', compact('summary', 'stats', 'trend', 'pendingIzin', 'todayAttendances'));
    }

    public function leaderboard()
    {
        $kelasList   = Kelas::orderBy('nama_kelas')->get();
        $leaderboard = $this->leaderboardService->getRankings();
        return view('admin.leaderboard', compact('kelasList', 'leaderboard'));
    }
}
