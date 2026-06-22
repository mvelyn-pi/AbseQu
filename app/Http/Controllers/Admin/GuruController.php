<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $guru = $query->orderBy('nama')->paginate(20)->withQueryString();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'no_whatsapp'  => 'nullable|string|max:20',
        ]);

        $validated['role']     = 'guru';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', "Akun guru {$validated['nama']} berhasil dibuat.");
    }

    public function edit(User $guru)
    {
        abort_if($guru->role !== 'guru', 404);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        abort_if($guru->role !== 'guru', 404);

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $guru->id,
            'no_whatsapp' => 'nullable|string|max:20',
            'password'    => 'nullable|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $guru->update($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', "Data guru {$guru->nama} berhasil diperbarui.");
    }

    public function destroy(User $guru)
    {
        abort_if($guru->role !== 'guru', 404);
        $guru->delete();
        return redirect()->route('admin.guru.index')
            ->with('success', 'Akun guru berhasil dihapus.');
    }
}
