<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'orangtua');
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        $orangtua = $query->orderBy('nama')->paginate(20)->withQueryString();
        return view('admin.orangtua.index', compact('orangtua'));
    }

    public function create()
    {
        return view('admin.orangtua.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'no_whatsapp'  => 'nullable|string|max:20',
        ]);

        $validated['role']     = 'orangtua';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.orangtua.index')
            ->with('success', "Akun Orang Tua {$validated['nama']} berhasil dibuat.");
    }

    public function edit(User $orangtua)
    {
        abort_if($orangtua->role !== 'orangtua', 404);
        return view('admin.orangtua.edit', compact('orangtua'));
    }

    public function update(Request $request, User $orangtua)
    {
        abort_if($orangtua->role !== 'orangtua', 404);

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $orangtua->id,
            'no_whatsapp' => 'nullable|string|max:20',
            'password'    => 'nullable|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $orangtua->update($validated);

        return redirect()->route('admin.orangtua.index')
            ->with('success', "Data Orang Tua {$orangtua->nama} berhasil diperbarui.");
    }

    public function destroy(User $orangtua)
    {
        abort_if($orangtua->role !== 'orangtua', 404);
        $orangtua->delete();
        return redirect()->route('admin.orangtua.index')
            ->with('success', 'Akun Orang Tua berhasil dihapus.');
    }
}
