<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Services\FonnteService;

class NotificationController extends Controller
{
    public function __construct(private FonnteService $fonnteService) {}

    public function index()
    {
        $logs = NotificationLog::with('student.kelas')
            ->latest()
            ->paginate(30);

        $todaySuccess = NotificationLog::whereDate('tanggal_kirim', today())->where('status_kirim', 'success')->count();
        $todayFailed  = NotificationLog::whereDate('tanggal_kirim', today())->where('status_kirim', 'failed')->count();

        return view('admin.notifikasi.index', compact('logs', 'todaySuccess', 'todayFailed'));
    }

    public function sendBulkAlpha()
    {
        $result = $this->fonnteService->notifyAllAlphaToday();

        return back()->with(
            'success',
            "Notifikasi dikirim: {$result['success']} berhasil, {$result['failed']} gagal."
        );
    }

    public function clearLogs()
    {
        NotificationLog::truncate();
        return back()->with('success', 'Semua riwayat log notifikasi telah dibersihkan.');
    }
}
