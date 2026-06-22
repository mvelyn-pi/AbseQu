<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class ParentPortalController extends Controller
{
    private function getChild(): Student
    {
        $child = auth()->user()->students()->where('aktif', true)->first();
        abort_if(!$child, 404, 'Data anak tidak ditemukan. Hubungi admin.');
        return $child;
    }

    public function dashboard()
    {
        $child = $this->getChild();

        $bulanIni = Attendance::where('student_id', $child->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->get();

        $summary = [
            'hadir'     => $bulanIni->whereIn('status', ['Hadir', 'Terlambat'])->count(),
            'terlambat' => $bulanIni->where('status', 'Terlambat')->count(),
            'izin'      => $bulanIni->where('status', 'Izin')->count(),
            'sakit'     => $bulanIni->where('status', 'Sakit')->count(),
            'alpha'     => $bulanIni->where('status', 'Alpha')->count(),
            'total'     => $bulanIni->count(),
            'persentase'=> $bulanIni->count() > 0
                ? round($bulanIni->whereIn('status', ['Hadir', 'Terlambat'])->count() / $bulanIni->count() * 100, 1)
                : 0,
        ];

        // Last 30 days trend
        $trend = collect(range(29, 0))->map(function ($daysAgo) use ($child) {
            $date = now()->subDays($daysAgo);
            $att  = Attendance::where('student_id', $child->id)->whereDate('tanggal', $date)->first();
            return [
                'date'   => $date->format('d/m'),
                'status' => $att?->status ?? 'N/A',
            ];
        });

        $todayAtt = $child->getAttendanceToday();

        // Leaderboard position in class
        $rankData = $this->getClassRank($child);

        $pendingIzin = $child->leaveRequests()->where('status', 'Pending')->count();

        return view('orangtua.dashboard', compact('child', 'summary', 'trend', 'todayAtt', 'rankData', 'pendingIzin'));
    }

    public function attendance(Request $request)
    {
        $child = $this->getChild();

        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$year, $month] = explode('-', $bulan);

        $attendances = Attendance::where('student_id', $child->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->get();

        return view('orangtua.absensi', compact('child', 'attendances', 'bulan'));
    }

    private function getClassRank(Student $child): array
    {
        $classStudents = Student::where('kelas_id', $child->kelas_id)->where('aktif', true)->get();

        $ranked = $classStudents->map(function ($s) {
            return [
                'id'         => $s->id,
                'persentase' => $s->getPersentaseKehadiran(now()->month, now()->year),
            ];
        })->sortByDesc('persentase')->values();

        $rank = $ranked->search(fn($r) => $r['id'] === $child->id);

        return [
            'rank'  => $rank !== false ? $rank + 1 : '-',
            'total' => $classStudents->count(),
        ];
    }
}
