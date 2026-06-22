<x-app-layout>
    <x-slot name="title">Scanner QR</x-slot>

    <div class="flex flex-col md:flex-row gap-lg h-full" style="min-height: calc(100vh - 120px)">

        {{-- Left Column: Scanner Area --}}
        <div class="flex-1 flex flex-col items-center justify-start">
            <div class="w-full text-center mb-lg">
                <h2 class="font-headline-lg text-headline-lg text-on-background mb-xs">Portal Presensi Scanner</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Sistem terhubung secara real-time. Mode online aktif.</p>
            </div>

            {{-- Scanner Viewport --}}
            <div class="relative w-full max-w-2xl bg-surface-container-low border border-primary-container rounded-xl overflow-hidden flex items-center justify-center" style="aspect-ratio: 4/3;">
                {{-- Grid pattern --}}
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#1b1c1c 1px, transparent 1px); background-size: 20px 20px;"></div>
                {{-- Corner marks --}}
                <div class="absolute inset-lg camera-overlay pointer-events-none"></div>
                {{-- Scanning line --}}
                <div class="absolute left-0 right-0 h-[2px] bg-secondary animate-scan-line z-10" style="top: 0; transform-origin: top;"></div>
                {{-- QR reader --}}
                <div id="reader" class="w-full h-full absolute inset-0 z-20 opacity-0 transition-opacity"></div>
                {{-- Idle placeholder --}}
                <div id="scanner-idle" class="flex flex-col items-center justify-center text-on-surface-variant opacity-50 z-10">
                    <span class="material-symbols-outlined mb-sm" style="font-size:48px">qr_code_scanner</span>
                    <span class="font-body-lg text-body-lg">Kamera Aktif</span>
                </div>
            </div>

            {{-- Controls --}}
            <div class="w-full max-w-2xl mt-lg flex items-center justify-between px-sm">
                <p class="font-body-lg text-body-lg text-on-surface-variant flex items-center gap-xs">
                    <span class="material-symbols-outlined text-primary" style="font-size:20px">info</span>
                    Arahkan ID Card ke kamera
                </p>
                <button id="startScanBtn" title="Mulai Kamera" class="flex items-center gap-xs px-md py-sm rounded-lg border border-primary-container text-primary-container font-button-text text-button-text hover:bg-primary-container hover:text-on-primary transition-colors active:border-[2px]">
                    <span class="material-symbols-outlined" style="font-size:18px">flip_camera_ios</span>
                    <span class="hidden md:inline">Mulai Kamera</span>
                </button>
            </div>

            {{-- Manual input --}}
            <div class="w-full max-w-2xl mt-lg">
                <form id="manualForm" method="POST" action="{{ route('guru.scan') }}" class="flex gap-md">
                    @csrf
                    <input type="text" name="qr_code" id="qrInput" placeholder="Atau ketik NIS/QR Code manual..."
                        class="flex-1 h-10 px-sm border border-outline-variant bg-surface-container-lowest rounded font-body-sm text-body-sm text-on-surface focus:outline-none focus:border-primary-container focus:ring-0 transition-colors">
                    <button type="submit" class="flex items-center gap-xs px-md py-sm bg-primary-container text-on-primary rounded font-button-text text-button-text hover:bg-primary transition-colors active:scale-95">
                        <span class="material-symbols-outlined" style="font-size:18px">send</span>
                        Proses
                    </button>
                </form>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="w-full max-w-2xl mt-md p-sm rounded-lg bg-secondary-container border border-secondary flex items-center gap-sm">
                <span class="material-symbols-outlined text-secondary">check_circle</span>
                <span class="font-body-sm text-body-sm text-on-secondary-container font-semibold">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="w-full max-w-2xl mt-md p-sm rounded-lg bg-error-container border border-error flex items-center gap-sm">
                <span class="material-symbols-outlined text-error">error</span>
                <span class="font-body-sm text-body-sm text-on-error-container font-semibold">{{ session('error') }}</span>
            </div>
            @endif
        </div>

        {{-- Right Column: Result Panel --}}
        <aside class="w-full md:w-[380px] flex-shrink-0 h-full flex flex-col gap-md">
            {{-- Result card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col flex-1">
                <div class="border-b border-outline-variant pb-md mb-md flex items-center justify-between">
                    <h3 class="font-headline-md text-headline-md text-on-background flex items-center gap-xs">
                        <span class="material-symbols-outlined text-secondary">check_circle</span>
                        Siswa Terdeteksi
                    </h3>
                    <span class="font-label-bold text-label-bold text-secondary bg-secondary-container px-sm py-base rounded-full uppercase">Real-time</span>
                </div>

                @if(session('scan_result'))
                @php $r = session('scan_result'); @endphp
                <div class="flex flex-col gap-lg flex-1">
                    {{-- Avatar & name --}}
                    <div class="flex items-center gap-md">
                        <div class="w-16 h-16 rounded-full border border-outline-variant bg-surface-container overflow-hidden flex-shrink-0">
                            <img src="{{ $r['foto_url'] ?? '' }}" alt="Foto"
                                class="w-full h-full object-cover grayscale opacity-80"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:40px;display:none">person</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider mb-base">Nama Lengkap</span>
                            <span class="font-body-lg text-body-lg text-on-background font-semibold">{{ $r['name'] ?? '-' }}</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">NIS: {{ $r['nis'] ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Details Bento Grid --}}
                    <div class="grid grid-cols-2 gap-sm">
                        <div class="border border-outline-variant rounded-lg p-sm bg-surface">
                            <span class="font-label-bold text-label-bold text-on-surface-variant block mb-base">Kelas</span>
                            <span class="font-body-lg text-body-lg text-on-background">{{ $r['kelas'] ?? '-' }}</span>
                        </div>
                        <div class="border border-outline-variant rounded-lg p-sm bg-surface">
                            <span class="font-label-bold text-label-bold text-on-surface-variant block mb-base">Waktu Scan</span>
                            <span class="font-body-lg text-body-lg text-on-background">{{ $r['time'] ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Status Highlight --}}
                    <div class="border border-outline-variant rounded-lg p-md bg-primary-fixed/20 flex flex-col items-center justify-center mt-auto border-t-4 {{ ($r['status'] ?? '') === 'terlambat' ? 'border-t-[#FBC02D]' : 'border-t-secondary' }}">
                        <span class="font-label-bold text-label-bold text-on-surface-variant uppercase mb-xs">Status Kehadiran</span>
                        <div class="font-headline-md text-headline-md px-lg py-sm rounded-full w-full text-center
                            {{ ($r['status'] ?? '') === 'hadir' ? 'bg-secondary text-on-secondary' : '' }}
                            {{ ($r['status'] ?? '') === 'terlambat' ? 'bg-[#FBC02D] text-[#3E2723]' : '' }}
                            {{ ($r['status'] ?? '') === 'alpha' ? 'bg-error text-on-error' : '' }}">
                            {{ strtoupper($r['label'] ?? $r['status'] ?? 'N/A') }}
                        </div>
                    </div>
                </div>
                @else
                <div class="flex-1 flex flex-col items-center justify-center text-on-surface-variant opacity-50">
                    <span class="material-symbols-outlined mb-md" style="font-size:64px">person_search</span>
                    <p class="font-body-lg text-body-lg text-center">Scan QR siswa untuk<br>melihat hasil di sini</p>
                </div>
                @endif
            </div>

            {{-- System Status Indicator --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-sm flex items-center justify-between">
                <div class="flex items-center gap-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                    </span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Koneksi Stabil</span>
                </div>
                <span class="font-label-bold text-label-bold text-on-surface-variant">AbsenQu v2.0</span>
            </div>
        </aside>
    </div>

    <x-slot name="scripts">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const startBtn = document.getElementById('startScanBtn');
        const readerEl = document.getElementById('reader');
        const idleEl  = document.getElementById('scanner-idle');
        const qrInput = document.getElementById('qrInput');
        const form    = document.getElementById('manualForm');
        let html5QrCode = null;

        startBtn.addEventListener('click', () => {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode = null;
                    readerEl.style.opacity = '0';
                    idleEl.style.display = 'flex';
                    startBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">flip_camera_ios</span> <span class="hidden md:inline">Mulai Kamera</span>';
                }).catch(err => {
                    console.error("Gagal menghentikan kamera", err);
                });
                return;
            }
            html5QrCode = new Html5Qrcode("reader");
            readerEl.style.opacity = '1';
            idleEl.style.display = 'none';
            startBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">stop</span> <span class="hidden md:inline">Stop Kamera</span>';

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10 },
                (decodedText) => {
                    html5QrCode.stop().then(() => {
                        html5QrCode = null;
                        readerEl.style.opacity = '0';
                        idleEl.style.display = 'flex';
                        startBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">flip_camera_ios</span> <span class="hidden md:inline">Mulai Kamera</span>';
                        qrInput.value = decodedText;
                        form.submit();
                    }).catch(err => {
                        console.error("Error stopping QR Code", err);
                        qrInput.value = decodedText;
                        form.submit();
                    });
                },
                (errorMessage) => {
                    // console.log(errorMessage); // silent during scanning
                }
            ).catch(err => {
                alert("Kamera gagal diakses. Pastikan Anda mengizinkan akses kamera dan menggunakan HTTPS atau localhost. Error: " + err);
                html5QrCode = null;
                readerEl.style.opacity = '0';
                idleEl.style.display = 'flex';
                startBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px">flip_camera_ios</span> <span class="hidden md:inline">Mulai Kamera</span>';
            });
        });
    </script>
    </x-slot>
</x-app-layout>
