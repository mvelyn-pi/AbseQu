<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Kelas;
use App\Models\Student;

class LeaderboardService
{
    /**
     * Get ranked students by attendance percentage.
     *
     * @param int|null $kelasId  Filter by class (null = all classes)
     * @param string   $periode  'minggu' | 'bulan' | 'semester' | 'all'
     * @param int      $limit    Max rows to return
     */
    public function getRankings(?int $kelasId = null, string $periode = 'bulan', int $limit = 50): array
    {
        [$startDate, $endDate] = $this->getPeriodeDates($periode);

        $query = Student::query()
            ->with(['kelas', 'attendances' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            }])
            ->where('aktif', true);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $students = $query->get();

        $ranked = $students->map(function ($student) use ($startDate, $endDate) {
            $attendances = $student->attendances;
            $total       = $attendances->count();
            $hadir       = $attendances->whereIn('status', ['Hadir', 'Terlambat'])->count();
            $terlambat   = $attendances->where('status', 'Terlambat')->count();
            $alpha       = $attendances->where('status', 'Alpha')->count();
            $persentase  = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            return [
                'student'    => $student,
                'total'      => $total,
                'hadir'      => $hadir,
                'terlambat'  => $terlambat,
                'alpha'      => $alpha,
                'persentase' => $persentase,
                'is_perfect' => $persentase >= 100 && $total > 0,
            ];
        })
        ->sortByDesc('persentase')
        ->values()
        ->take($limit)
        ->toArray();

        // Add rank number
        foreach ($ranked as $i => &$item) {
            $item['rank'] = $i + 1;
        }

        return $ranked;
    }

    private function getPeriodeDates(string $periode): array
    {
        return match($periode) {
            'minggu'   => [now()->startOfWeek(), now()->endOfWeek()],
            'bulan'    => [now()->startOfMonth(), now()->endOfMonth()],
            'semester' => $this->getSemesterDates(),
            default    => [now()->startOfYear(), now()->endOfYear()],
        };
    }

    private function getSemesterDates(): array
    {
        $month = now()->month;
        if ($month >= 7) {
            // Semester ganjil: Juli - Desember
            return [now()->setMonth(7)->startOfMonth(), now()->setMonth(12)->endOfMonth()];
        } else {
            // Semester genap: Januari - Juni
            return [now()->startOfYear(), now()->setMonth(6)->endOfMonth()];
        }
    }
}
