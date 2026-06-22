<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class ParentLeaveRequestController extends Controller
{
    private function getChild()
    {
        return auth()->user()->students()->where('aktif', true)->firstOrFail();
    }

    public function index()
    {
        $child  = $this->getChild();
        $leaves = LeaveRequest::where('student_id', $child->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('orangtua.izin.index', compact('leaves', 'child'));
    }

    public function create()
    {
        $child = $this->getChild();

        // Check submission deadline
        $batasDays = (int) Setting::get('batas_pengajuan_izin', 1);
        $minDate   = today()->format('Y-m-d');
        $maxDate   = today()->addDays($batasDays)->format('Y-m-d');

        $previousRequests = LeaveRequest::where('student_id', $child->id)
            ->latest()
            ->take(5)
            ->get();

        return view('orangtua.izin.create', compact('child', 'minDate', 'maxDate', 'previousRequests'));
    }

    public function store(Request $request)
    {
        $child = $this->getChild();

        $batasDays = (int) Setting::get('batas_pengajuan_izin', 1);

        $validated = $request->validate([
            'tanggal_izin' => [
                'required', 'date',
                'after_or_equal:' . today()->subDays($batasDays)->format('Y-m-d'),
                'before_or_equal:' . today()->addDay()->format('Y-m-d'),
            ],
            'jenis'  => 'required|in:Izin,Sakit',
            'alasan' => 'required|string|max:500',
            'bukti'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('izin-bukti', 'public');
        }

        // Check if there's already a pending/approved request for same date
        $existing = LeaveRequest::where('student_id', $child->id)
            ->where('tanggal_izin', $validated['tanggal_izin'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->first();

        if ($existing) {
            return back()->withErrors(['tanggal_izin' => 'Sudah ada pengajuan izin untuk tanggal tersebut.']);
        }

        LeaveRequest::create([
            'student_id'   => $child->id,
            'requested_by' => auth()->id(),
            'tanggal_izin' => $validated['tanggal_izin'],
            'jenis'        => $validated['jenis'],
            'alasan'       => $validated['alasan'],
            'bukti'        => $validated['bukti'] ?? null,
            'status'       => 'Pending',
        ]);

        return redirect()->route('orangtua.izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan guru.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $child = $this->getChild();
        abort_if($leaveRequest->student_id !== $child->id, 403);

        $leaveRequest->load(['diprosesOleh']);
        return view('orangtua.izin.show', compact('leaveRequest', 'child'));
    }
}
