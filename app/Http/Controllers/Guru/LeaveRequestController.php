<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['student.kelas', 'requestedBy'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('student', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }

        $leaveRequests = $query->paginate(20)->withQueryString();
        $pendingCount  = LeaveRequest::where('status', 'Pending')->count();

        return view('guru.izin.index', compact('leaveRequests', 'pendingCount'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['student.kelas', 'requestedBy', 'diprosesOleh']);
        return view('guru.izin.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        abort_if(!$leaveRequest->isPending(), 422, 'Pengajuan ini sudah diproses.');

        $request->validate(['catatan_guru' => 'nullable|string|max:500']);

        $leaveRequest->update([
            'status'       => 'Approved',
            'catatan_guru' => $request->catatan_guru,
            'diproses_oleh'=> auth()->id(),
            'diproses_at'  => now(),
        ]);

        // Update attendance record: Alpha → Izin/Sakit
        Attendance::updateOrCreate(
            [
                'student_id' => $leaveRequest->student_id,
                'tanggal'    => $leaveRequest->tanggal_izin,
            ],
            [
                'status'     => $leaveRequest->jenis, // 'Izin' or 'Sakit'
                'keterangan' => $leaveRequest->alasan,
                'dicatat_oleh' => auth()->id(),
            ]
        );

        return back()->with('success', 'Pengajuan izin disetujui dan absensi diperbarui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        abort_if(!$leaveRequest->isPending(), 422, 'Pengajuan ini sudah diproses.');

        $request->validate(['catatan_guru' => 'required|string|max:500']);

        $leaveRequest->update([
            'status'       => 'Rejected',
            'catatan_guru' => $request->catatan_guru,
            'diproses_oleh'=> auth()->id(),
            'diproses_at'  => now(),
        ]);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }
}
