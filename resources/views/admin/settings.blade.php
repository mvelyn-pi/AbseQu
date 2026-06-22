<x-app-layout>
    <x-slot name="title">Pengaturan Sistem</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-lg">
            <h2 class="page-title">Pengaturan Sistem</h2>
            <p class="page-subtitle">Atur parameter absensi, notifikasi, dan template pesan WhatsApp</p>
        </div>

        @if(session('success'))
        <div class="mb-lg p-sm rounded-lg bg-secondary-container border border-secondary flex items-center gap-sm">
            <span class="material-symbols-outlined text-secondary">check_circle</span>
            <span class="text-body-sm text-on-secondary-container font-semibold">{{ session('success') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="flex flex-col gap-lg">
            @csrf

            {{-- Time Settings --}}
            <div class="card">
                <div class="card-header bg-surface-container-low/40">
                    <h3 class="card-title flex items-center gap-xs">
                        <span class="material-symbols-outlined text-primary">schedule</span>
                        Waktu Absensi
                    </h3>
                </div>
                <div class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="jam_batas_hadir">JAM BATAS HADIR</label>
                        <input type="time" name="jam_batas_hadir" id="jam_batas_hadir"
                            value="{{ $settings['jam_batas_hadir'] ?? '07:30' }}"
                            class="form-input rounded">
                        <p class="text-xs text-on-surface-variant">Scan setelah jam ini = Terlambat</p>
                    </div>
                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="jam_tutup_absensi">JAM TUTUP ABSENSI</label>
                        <input type="time" name="jam_tutup_absensi" id="jam_tutup_absensi"
                            value="{{ $settings['jam_tutup_absensi'] ?? '10:00' }}"
                            class="form-input rounded">
                        <p class="text-xs text-on-surface-variant">Tidak scan setelah ini = Alpha</p>
                    </div>
                </div>
            </div>

            {{-- WhatsApp / Fonnte --}}
            <div class="card">
                <div class="card-header bg-surface-container-low/40">
                    <h3 class="card-title flex items-center gap-xs">
                        <span class="material-symbols-outlined text-secondary">phone_android</span>
                        Notifikasi WhatsApp (Fonnte)
                    </h3>
                </div>
                <div class="p-lg flex flex-col gap-lg">
                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="fonnte_api_key">FONNTE API KEY</label>
                        <input type="text" name="fonnte_api_key" id="fonnte_api_key"
                            value="{{ $settings['fonnte_api_key'] ?? '' }}"
                            placeholder="Masukkan API key Fonnte Anda..."
                            class="form-input rounded font-mono">
                        <div class="p-md rounded-lg bg-secondary-container/20 border border-secondary-container">
                            <p class="text-body-sm text-secondary font-semibold mb-sm">Cara mendapatkan Fonnte API Key:</p>
                            <ol class="text-body-sm text-on-surface-variant space-y-xs list-decimal list-inside">
                                <li>Daftar di <a href="https://fonnte.com" target="_blank" class="text-primary underline">fonnte.com</a></li>
                                <li>Masuk ke dashboard → pilih "Devices"</li>
                                <li>Tambahkan device (scan QR WhatsApp)</li>
                                <li>Salin token/API key dari halaman device</li>
                                <li>Tempelkan di kolom di atas lalu simpan</li>
                            </ol>
                        </div>
                    </div>

                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="template_pesan_wa">TEMPLATE PESAN ALPHA</label>
                        <textarea name="template_pesan_wa" id="template_pesan_wa" rows="6" class="form-input rounded resize-y font-mono text-sm">{{ $settings['template_pesan_wa'] ?? "Yth. Orang Tua/Wali {nama_siswa},\n\nKami informasikan bahwa {nama_siswa} dari kelas {kelas} TIDAK HADIR pada tanggal {tanggal} tanpa keterangan (Alpha).\n\nMohon konfirmasi kehadiran ke pihak sekolah.\n\nSalam,\nSMP 1 Palopo" }}</textarea>
                        <p class="text-xs text-on-surface-variant">Variabel: {nama_siswa}, {kelas}, {tanggal}</p>
                    </div>
                </div>
            </div>

            {{-- School Info --}}
            <div class="card">
                <div class="card-header bg-surface-container-low/40">
                    <h3 class="card-title flex items-center gap-xs">
                        <span class="material-symbols-outlined text-primary">school</span>
                        Informasi Sekolah
                    </h3>
                </div>
                <div class="p-lg grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="nama_sekolah">NAMA SEKOLAH</label>
                        <input type="text" name="nama_sekolah" id="nama_sekolah"
                            value="{{ $settings['nama_sekolah'] ?? 'SMP 1 Palopo' }}"
                            class="form-input rounded">
                    </div>
                    <div class="flex flex-col gap-base">
                        <label class="form-label" for="batas_pengajuan_izin">BATAS HARI PENGAJUAN IZIN (H+n)</label>
                        <input type="number" name="batas_pengajuan_izin" id="batas_pengajuan_izin"
                            value="{{ $settings['batas_pengajuan_izin'] ?? '1' }}"
                            min="0" max="7" class="form-input rounded w-32">
                        <p class="text-xs text-on-surface-variant">0 = hanya hari H, 1 = max H+1, dst.</p>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit" class="btn-primary rounded-lg">
                    <span class="material-symbols-outlined" style="font-size:18px">save</span>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
