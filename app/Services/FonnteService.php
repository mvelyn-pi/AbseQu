<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key', env('FONNTE_API_KEY', ''));
    }

    /**
     * Send a WhatsApp message via Fonnte API.
     */
    public function sendMessage(string $noWa, string $message): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'Fonnte API key belum dikonfigurasi.'];
        }

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders(['Authorization' => $this->apiKey])
                ->post($this->apiUrl, [
                    'target'  => $noWa,
                    'message' => $message,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                return ['success' => true, 'response' => $body];
            }

            return [
                'success'  => false,
                'message'  => $body['reason'] ?? $body['detail'] ?? $body['message'] ?? 'Gagal mengirim pesan.',
                'response' => $body,
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Send alpha notification to a student's parent.
     */
    public function notifyAlpha(Student $student): bool
    {
        $parent = $student->parentUser;
        if (!$parent || empty($parent->no_whatsapp)) {
            return false;
        }

        $template = Setting::get(
            'template_pesan_wa',
            "Assalamu'alaikum Wr. Wb. / Selamat Pagi/Siang,\n" .
                "Yth. Orang Tua/Wali dari ananda *{nama_siswa}*.\n\n" .
                "Semoga Bapak/Ibu dalam keadaan sehat. Kami ingin mengonfirmasi mengenai kehadiran ananda *{nama_siswa}* dari kelas *{kelas}* yang pada hari ini, *{tanggal}*, belum hadir di sekolah tanpa keterangan (Alpha).\n\n" .
                "Demi keamanan dan ketertiban bersama, mohon hubungi pihak wali kelas untuk konfirmasi lebih lanjut.\n\n" .
                "Atas perhatian Bapak/Ibu, kami ucapkan terima kasih.\n\n" .
                "Hormat kami,\n" .
                "*SMPN 1 Palopo*"
        );

        $pesan = str_replace(
            ['{nama_siswa}', '{kelas}', '{tanggal}'],
            [$student->nama, $student->kelas->nama_kelas ?? '-', today()->translatedFormat('d F Y')],
            $template
        );

        $result = $this->sendMessage($parent->no_whatsapp, $pesan);

        // Log the notification
        NotificationLog::create([
            'student_id'      => $student->id,
            'tanggal_kirim'   => today(),
            'status_kirim'    => $result['success'] ? 'success' : 'failed',
            'no_tujuan'       => $parent->no_whatsapp,
            'isi_pesan'       => $pesan,
            'error_message'   => $result['success'] ? null : ($result['message'] ?? null),
            'fonnte_response' => isset($result['response']) ? json_encode($result['response']) : null,
        ]);

        return $result['success'];
    }

    /**
     * Send bulk alpha notifications for today.
     */
    public function notifyAllAlphaToday(): array
    {
        $alphaAttendances = \App\Models\Attendance::whereDate('tanggal', today())
            ->where('status', 'Alpha')
            ->with(['student.parentUser', 'student.kelas'])
            ->get();

        $success = 0;
        $failed  = 0;

        foreach ($alphaAttendances as $att) {
            if ($this->notifyAlpha($att->student)) {
                $success++;
            } else {
                $failed++;
            }
        }

        return compact('success', 'failed');
    }
}
