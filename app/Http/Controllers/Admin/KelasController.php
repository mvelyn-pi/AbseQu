<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'students'])->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = User::where('role', 'guru')->orderBy('nama')->get();
        return view('admin.kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'    => 'required|string|max:50',
            'tingkat'       => 'required|in:VII,VIII,IX',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran'  => 'required|integer|min:2020|max:2050',
        ]);

        Kelas::create($validated);
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$validated['nama_kelas']} berhasil dibuat.");
    }

    public function edit(Kelas $kela)
    {
        $guru = User::where('role', 'guru')->orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kela', 'guru'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas'    => 'required|string|max:50',
            'tingkat'       => 'required|in:VII,VIII,IX',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran'  => 'required|integer|min:2020|max:2050',
        ]);

        $kela->update($validated);
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$kela->nama_kelas} berhasil diperbarui.");
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
