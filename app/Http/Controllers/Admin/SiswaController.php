<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Student;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function __construct(private QrCodeService $qrCodeService) {}

    public function index(Request $request)
    {
        $query = Student::with('kelas')->where('aktif', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $students = $query->orderBy('nama')->paginate(20)->withQueryString();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('students', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $parents   = \App\Models\User::where('role', 'orangtua')->orderBy('nama')->get();
        return view('admin.siswa.create', compact('kelasList', 'parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'nis'           => 'required|string|max:20|unique:students',
            'kelas_id'      => 'required|exists:classes,id',
            'parent_user_id'=> 'nullable|exists:users,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string|max:255',
            'foto'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        Student::create($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa {$validated['nama']} berhasil ditambahkan.");
    }

    public function show(Student $siswa)
    {
        $siswa->load(['kelas', 'parentUser', 'attendances' => fn($q) => $q->latest()->limit(30)]);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Student $siswa)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $parents   = \App\Models\User::where('role', 'orangtua')->orderBy('nama')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelasList', 'parents'));
    }

    public function update(Request $request, Student $siswa)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'nis'           => 'required|string|max:20|unique:students,nis,' . $siswa->id,
            'kelas_id'      => 'required|exists:classes,id',
            'parent_user_id'=> 'nullable|exists:users,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string|max:255',
            'foto'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Data siswa {$siswa->nama} berhasil diperbarui.");
    }

    public function destroy(Student $siswa)
    {
        $siswa->update(['aktif' => false]);
        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa {$siswa->nama} berhasil dinonaktifkan.");
    }

    public function showQr(Student $siswa)
    {
        $qrSvg = $this->qrCodeService->generateSvg($siswa, 250);
        return view('admin.siswa.qr', compact('siswa', 'qrSvg'));
    }

    public function downloadQr(Student $siswa)
    {
        $svg = $this->qrCodeService->generateSvg($siswa, 150);
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', "attachment; filename=\"QR_{$siswa->nis}_{$siswa->nama}.svg\"");
    }

    public function printIdCard(Student $siswa)
    {
        $qrSvg = $this->qrCodeService->generateSvg($siswa, 180);
        return view('admin.siswa.idcard', compact('siswa', 'qrSvg'));
    }
}
