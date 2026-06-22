@extends('layouts.app')
@section('title', 'Edit Siswa')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.siswa.index') }}" class="text-[#717a6d] hover:text-[#1b5e20] transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-[#00450d]">Edit Data Siswa</h2>
            <p class="text-sm text-[#717a6d] mt-0.5">Perbarui informasi data siswa</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 p-3 rounded-lg bg-[#ffdad6] border border-[#ba1a1a]/30 flex items-start gap-2">
        <span class="material-symbols-outlined text-[#D32F2F] flex-shrink-0 mt-0.5" style="font-size:18px">error</span>
        <ul class="text-sm text-[#D32F2F]">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
        {{-- Student info header --}}
        <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2] flex items-center gap-4">
            <img src="{{ $siswa->foto_url }}" class="w-16 h-16 rounded-xl object-cover border border-[#c0c9bb]" alt="{{ $siswa->nama }}">
            <div>
                <h3 class="font-bold text-[#1b1c1c]">{{ $siswa->nama }}</h3>
                <p class="text-sm text-[#717a6d]">NIS: {{ $siswa->nis }}</p>
                <code class="text-xs text-[#1b5e20] font-mono bg-[#F1F8E9] px-2 py-0.5 rounded">{{ $siswa->qr_code }}</code>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2 flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="nama">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}" required
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors">
                    @error('nama')<p class="text-xs text-[#D32F2F]">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">NIS *</label>
                    <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors font-mono">
                    @error('nis')<p class="text-xs text-[#D32F2F]">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Kelas *</label>
                    <select name="kelas_id" required
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}"
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors">
                </div>

                <div class="sm:col-span-2 flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Akun Orang Tua</label>
                    <select name="parent_user_id"
                        class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                        <option value="">— Tidak ada —</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_user_id', $siswa->parent_user_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Alamat</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-3 py-2 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors resize-y">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>

                <div class="sm:col-span-2 flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Foto Baru (kosongkan jika tidak diubah)</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full text-sm text-[#717a6d] border border-[#c0c9bb] rounded-lg px-3 py-2 file:mr-3 file:px-3 file:py-1 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#1b5e20] file:text-white hover:file:bg-[#00450d]">
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-[#c0c9bb]">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1b5e20] text-white text-sm font-semibold rounded-lg hover:bg-[#00450d] transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#c0c9bb] text-[#1b5e20] text-sm font-semibold rounded-lg hover:bg-[#f6f3f2] transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
