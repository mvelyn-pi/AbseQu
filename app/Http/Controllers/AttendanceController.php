<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Models\Attendance;
use App\Models\Kelas;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private AttendanceService $service) {}

    /** Scanner page for guru */
    public function scannerPage()
    {
        $summary = $this->service->getTodaySummary();
        return view('guru.scanner', compact('summary'));
    }

    public function processQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);

        $result = $this->service->processQrScan(
            $request->qr_code,
            auth()->id()
        );

        if (!isset($result['student'])) {
            return back()->with('error', $result['message']);
        }

        $student = $result['student'];
        $attendance = $result['attendance'] ?? null;
        $student->load('kelas'); // ensure kelas relation is loaded

        $scanResult = [
            'foto_url' => $student->foto ? asset('storage/' . $student->foto) : null,
            'name'     => $student->nama,
            'nis'      => $student->nis,
            'kelas'    => $student->kelas->nama_kelas ?? '-',
            'time'     => $attendance ? $attendance->waktu_scan : '-',
            'status'   => strtolower($attendance->status ?? 'terdeteksi'),
            'label'    => $attendance->status ?? 'Terdeteksi',
        ];

        if ($result['success']) {
            return back()
                ->with('success', $result['message'])
                ->with('scan_result', $scanResult);
        } else {
            return back()
                ->with('error', $result['message'])
                ->with('scan_result', $scanResult);
        }
    }

    /** Guru attendance table */
    public function guruIndex(Request $request)
    {
        $tanggal  = $request->get('tanggal', today()->format('Y-m-d'));
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId  = $request->get('kelas_id');

        $query = Attendance::with(['student.kelas'])
            ->whereDate('tanggal', $tanggal);

        if ($kelasId) {
            $query->byKelas($kelasId);
        }

        $attendances = $query->orderBy('waktu_scan')->paginate(30)->withQueryString();
        $summary     = $this->service->getTodaySummary($kelasId);

        return view('guru.absensi', compact('attendances', 'tanggal', 'kelasList', 'kelasId', 'summary'));
    }

    /** Admin attendance index */
    public function adminIndex(Request $request)
    {
        return $this->guruIndex($request);
    }

    /** Mark all unscanned students as Alpha */
    public function markAllAlpha()
    {
        $count = $this->service->markAlphaForToday();
        return back()->with('success', "{$count} siswa ditandai Alpha.");
    }

    /** Export attendance to CSV */
    public function export(Request $request)
    {
        $tanggal  = $request->get('tanggal', today()->format('Y-m-d'));
        $kelasId  = $request->get('kelas_id');

        $query = Attendance::with(['student.kelas'])
            ->whereDate('tanggal', $tanggal);

        if ($kelasId) {
            $query->byKelas($kelasId);
        }

        $attendances = $query->orderBy('waktu_scan')->get();

        $filename = "Rekap_Absensi_{$tanggal}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nama Siswa', 'Kelas', 'Tanggal', 'Waktu Scan', 'Status', 'Terlambat (Menit)', 'Keterangan'];

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($attendances as $att) {
                fputcsv($file, [
                    $att->student->nama ?? '-',
                    $att->student->kelas->nama_kelas ?? '-',
                    $att->tanggal->format('d/m/Y'),
                    $att->waktu_scan ? \Carbon\Carbon::parse($att->waktu_scan)->format('H:i') : '-',
                    $att->status,
                    $att->menit_terlambat ?? 0,
                    $att->keterangan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
