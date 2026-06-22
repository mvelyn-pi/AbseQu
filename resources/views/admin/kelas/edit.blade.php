<x-app-layout>
    <x-slot name="title">Edit Kelas</x-slot>

    <div class="max-w-3xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex items-center gap-md mb-xl">
            <a href="{{ route('admin.kelas.index') }}" class="p-xs bg-surface-container-low border border-outline-variant rounded-lg text-on-surface hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Data Kelas</h2>
                <p class="font-body-sm text-on-surface-variant">Ubah informasi kelas di bawah ini.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="p-sm rounded-lg bg-error-container border border-error flex flex-col gap-xs mb-lg">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-error">error</span>
                <span class="text-body-sm text-on-error-container font-semibold">Terdapat kesalahan pengisian form:</span>
            </div>
            <ul class="list-disc list-inside text-body-sm text-on-error-container ml-lg">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
            <div class="h-2 w-full bg-primary-container opacity-20"></div>

            <form action="{{ route('admin.kelas.update', $kela) }}" method="POST" class="p-lg md:p-xl space-y-lg">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="nama_kelas">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="tingkat">Tingkat</label>
                        <select name="tingkat" id="tingkat" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest appearance-none">
                            <option value="VII" {{ old('tingkat', $kela->tingkat) == 'VII' ? 'selected' : '' }}>VII</option>
                            <option value="VIII" {{ old('tingkat', $kela->tingkat) == 'VIII' ? 'selected' : '' }}>VIII</option>
                            <option value="IX" {{ old('tingkat', $kela->tingkat) == 'IX' ? 'selected' : '' }}>IX</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="wali_kelas_id">Wali Kelas</label>
                        <select name="wali_kelas_id" id="wali_kelas_id" class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest appearance-none">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" {{ old('wali_kelas_id', $kela->wali_kelas_id) == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="tahun_ajaran">Tahun Ajaran Awal</label>
                        <input type="number" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $kela->tahun_ajaran) }}" required min="2020" max="2050" class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>
                </div>

                <div class="flex flex-col-reverse md:flex-row justify-end gap-md pt-lg mt-xl border-t border-outline-variant">
                    <a href="{{ route('admin.kelas.index') }}" class="px-lg py-sm border border-outline-variant rounded-lg text-on-surface text-center hover:bg-surface-container transition-colors font-button-text text-button-text">Batal</a>
                    <button type="submit" class="flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-button-text text-button-text hover:bg-primary-container transition-colors active:scale-95">
                        <span class="material-symbols-outlined" style="font-size:18px">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
