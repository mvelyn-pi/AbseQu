<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Process a QR code scan and record attendance.
     * Returns an array with success status and message.
     */
    public function processQrScan(string $qrCode, int $scannedBy): array
    {
        // Find student by QR code
        $student = Student::where('qr_code', $qrCode)->where('aktif', true)->first();

        if (!$student) {
            return [
                'success' => false,
                'type' => 'not_found',
                'message' => 'QR Code tidak dikenali. Pastikan ID Card siswa valid.',
            ];
        }

        // Check if already scanned today
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('tanggal', today())
            ->first();

        if ($existing) {
            return [
                'success' => false,
                'type' => 'duplicate',
                'message' => "Siswa {$student->nama} sudah tercatat hadir hari ini pukul {$existing->waktu_scan}.",
                'student' => $student,
                'attendance' => $existing,
            ];
        }

        // Determine status: Hadir, Terlambat, or Alpha
        $now = Carbon::now();
        $batasHadir = Setting::get('jam_batas_hadir', '07:30');
        $batasTutup = Setting::get('jam_tutup_absensi', '10:00');

        $batasTime = Carbon::today()->setTimeFromTimeString($batasHadir);
        $tutupTime = Carbon::today()->setTimeFromTimeString($batasTutup);

        if ($now->lte($batasTime)) {
            $status = 'Hadir';
            $menitTerlambat = 0;
            $msg = "Selamat datang, {$student->nama}! Hadir tepat waktu.";
        } elseif ($now->lte($tutupTime)) {
            $status = 'Terlambat';
            $menitTerlambat = (int) $now->diffInMinutes($batasTime);
            $msg = "Selamat datang, {$student->nama}! Terlambat {$menitTerlambat} menit.";
        } else {
            $status = 'Alpha';
            $menitTerlambat = (int) $now->diffInMinutes($batasTime);
            $msg = "Maaf {$student->nama}, absensi ditutup. Anda tercatat Alpha karena melewati pukul {$batasTutup}.";
        }

        // Record attendance
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'tanggal' => today(),
            'waktu_scan' => $now->format('H:i:s'),
            'status' => $status,
            'menit_terlambat' => $menitTerlambat,
            'keterangan' => null,
            'dicatat_oleh' => $scannedBy,
        ]);

        return [
            'success' => true,
            'type' => strtolower($status),
            'message' => $msg,
            'student' => $student,
            'attendance' => $attendance,
        ];
    }

    /**
     * Mark all students who haven't scanned today as Alpha.
     * Should be called after jam_tutup_absensi.
     */
    public function markAlphaForToday(): int
    {
        $today = today();

        // Get all active students who don't have attendance today
        $absentStudents = Student::where('aktif', true)
            ->whereDoesntHave('attendances', fn($q) => $q->whereDate('tanggal', $today))
            ->get();

        $count = 0;
        foreach ($absentStudents as $student) {
            Attendance::create([
                'student_id' => $student->id,
                'tanggal' => $today,
                'waktu_scan' => null,
                'status' => 'Alpha',
                'menit_terlambat' => 0,
                'keterangan' => 'Otomatis ditandai Alpha setelah jam tutup absensi',
                'dicatat_oleh' => null,
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Get today's attendance summary for dashboard.
     */
    public function getTodaySummary(?int $kelasId = null): array
    {
        $query = Attendance::whereDate('tanggal', today());
        if ($kelasId) {
            $query->byKelas($kelasId);
        }

        $attendances = $query->get();

        return [
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'izin' => $attendances->where('status', 'Izin')->count(),
            'sakit' => $attendances->where('status', 'Sakit')->count(),
            'alpha' => $attendances->where('status', 'Alpha')->count(),
            'total' => $attendances->count(),
        ];
    }
}
