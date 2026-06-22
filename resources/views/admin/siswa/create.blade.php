<x-app-layout>
    <x-slot name="title">Tambah Siswa</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.siswa.index') }}" class="text-[#717a6d] hover:text-[#1b5e20] transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-[#00450d]">Tambah Siswa Baru</h2>
                <p class="text-sm text-[#717a6d] mt-0.5">Isi data siswa dan hubungkan dengan akun orang tua</p>
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
            <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2]">
                <h3 class="font-semibold text-[#1b1c1c]">Data Siswa</h3>
            </div>
            <form method="POST" action="{{ route('admin.siswa.store') }}" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="nama">Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors">
                        @error('nama')<p class="text-xs text-[#D32F2F]">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="nis">NIS *</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors font-mono">
                        @error('nis')<p class="text-xs text-[#D32F2F]">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="kelas_id">Kelas *</label>
                        <select name="kelas_id" id="kelas_id" required
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id')<p class="text-xs text-[#D32F2F]">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="jenis_kelamin">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors">
                    </div>

                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="parent_user_id">Akun Orang Tua</label>
                        <select name="parent_user_id" id="parent_user_id"
                            class="w-full h-10 px-3 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors appearance-none">
                            <option value="">— Belum ada / Tambahkan nanti —</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ old('parent_user_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider" for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2"
                            class="w-full px-3 py-2 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#1b5e20] transition-colors resize-y">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="sm:col-span-2 flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Foto Siswa</label>
                        <div class="flex items-start gap-4">
                            <div id="fotoPreviewWrap" class="hidden w-20 h-20 rounded-lg border border-[#c0c9bb] overflow-hidden flex-shrink-0">
                                <img id="fotoPreview" src="" class="w-full h-full object-cover">
                            </div>
                            <div class="border border-dashed border-[#c0c9bb] rounded-lg p-4 flex flex-col items-center justify-center gap-1 text-center hover:bg-[#f6f3f2] transition-colors cursor-pointer flex-1"
                                onclick="document.getElementById('foto').click()">
                                <span class="material-symbols-outlined text-[#717a6d] text-3xl">add_photo_alternate</span>
                                <p class="text-sm text-[#717a6d]">Klik untuk upload foto siswa</p>
                                <p class="text-xs text-[#717a6d] opacity-70">JPG, PNG max 2MB</p>
                                <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-[#c0c9bb]">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1b5e20] text-white text-sm font-semibold rounded-lg hover:bg-[#00450d] transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                        Simpan Siswa
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#c0c9bb] text-[#1b5e20] text-sm font-semibold rounded-lg hover:bg-[#f6f3f2] transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="scripts">
    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const wrap = document.getElementById('fotoPreviewWrap');
                    const preview = document.getElementById('fotoPreview');
                    preview.src = e.target.result;
                    wrap.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    </x-slot>
</x-app-layout>
