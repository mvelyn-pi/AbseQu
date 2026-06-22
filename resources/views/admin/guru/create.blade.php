<x-app-layout>
    <x-slot name="title">Tambah Guru</x-slot>

    <div class="max-w-3xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex items-center gap-md mb-xl">
            <a href="{{ route('admin.guru.index') }}" class="p-xs bg-surface-container-low border border-outline-variant rounded-lg text-on-surface hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Tambah Guru Baru</h2>
                <p class="font-body-sm text-on-surface-variant">Daftarkan akun guru untuk portal tenaga pendidik.</p>
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

            <form action="{{ route('admin.guru.store') }}" method="POST" class="p-lg md:p-xl space-y-lg">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="nama">Nama Lengkap Guru</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="no_whatsapp">Nomor WhatsApp</label>
                        <input type="text" name="no_whatsapp" id="no_whatsapp" value="{{ old('no_whatsapp') }}" class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest" placeholder="08123456789">
                    </div>

                    <div class="flex flex-col gap-base md:col-span-2">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="email">Alamat Email (Untuk Login)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="password">Password Baru</label>
                        <input type="password" name="password" id="password" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="font-label-bold text-label-bold text-on-surface-variant uppercase" for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="border border-outline-variant rounded-lg px-md py-sm font-body-lg text-body-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none bg-surface-container-lowest">
                    </div>
                </div>

                <div class="flex flex-col-reverse md:flex-row justify-end gap-md pt-lg mt-xl border-t border-outline-variant">
                    <a href="{{ route('admin.guru.index') }}" class="px-lg py-sm border border-outline-variant rounded-lg text-on-surface text-center hover:bg-surface-container transition-colors font-button-text text-button-text">Batal</a>
                    <button type="submit" class="flex items-center justify-center gap-xs px-lg py-sm bg-primary text-on-primary rounded-lg font-button-text text-button-text hover:bg-primary-container transition-colors active:scale-95">
                        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                        Simpan Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
