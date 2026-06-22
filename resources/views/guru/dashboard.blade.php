<x-app-layout>
    <x-slot name="title">Dashboard Guru</x-slot>

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#00450d]">Dashboard Guru</h2>
        <p class="text-sm text-[#717a6d] mt-1">Selamat datang, <strong class="text-[#1b1c1c]">{{ Auth::user()->name }}</strong> — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#4CAF50]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#2E7D32]" style="font-size:22px">how_to_reg</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Hadir</p>
                <p class="text-2xl font-bold text-[#2E7D32]">{{ $summary['hadir'] ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#FBC02D]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#E65100]" style="font-size:22px">schedule</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Terlambat</p>
                <p class="text-2xl font-bold text-[#E65100]">{{ $summary['terlambat'] ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#D32F2F]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#D32F2F]" style="font-size:22px">person_off</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Alpha</p>
                <p class="text-2xl font-bold text-[#D32F2F]">{{ $summary['alpha'] ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#006e1c]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#006e1c]" style="font-size:22px">article</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Izin Pending</p>
                <p class="text-2xl font-bold text-[#006e1c]">{{ $pendingIzin ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Scans Table --}}
    <div class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2] flex justify-between items-center">
            <h3 class="font-semibold text-[#1b1c1c]">Scan Terbaru Hari Ini</h3>
            <a href="{{ route('guru.absensi.index') }}" class="text-sm font-semibold text-[#00450d] hover:underline flex items-center gap-1">
                Lihat Semua <span class="material-symbols-outlined" style="font-size:16px">arrow_forward</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Jam Scan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentScans as $att)
                    <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                        <td class="px-6 py-3 text-sm font-semibold text-[#1b1c1c]">{{ $att->student->nama ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $att->student->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-[#717a6d] font-mono">{{ $att->waktu_scan ? \Carbon\Carbon::parse($att->waktu_scan)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-3">
                            @if($att->status === 'Hadir')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#4CAF50] text-white">Hadir</span>
                            @elseif($att->status === 'Terlambat')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FBC02D] text-[#3E2723]">Terlambat</span>
                            @elseif($att->status === 'Alpha')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#D32F2F] text-white">Alpha</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-[#717a6d]">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">event_busy</span>
                            Belum ada data kehadiran hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('guru.scanner') }}"
            class="bg-[#1b5e20] text-white rounded-xl p-5 flex items-center gap-4 hover:bg-[#00450d] transition-colors">
            <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1">qr_code_scanner</span>
            <div>
                <p class="font-semibold">Buka Scanner QR</p>
                <p class="text-xs opacity-80 mt-0.5">Scan kehadiran siswa sekarang</p>
            </div>
        </a>
        <a href="{{ route('guru.izin.index') }}"
            class="bg-white border border-[#c0c9bb] rounded-xl p-5 flex items-center gap-4 hover:bg-[#f6f3f2] transition-colors">
            <span class="material-symbols-outlined text-4xl text-[#006e1c]">article</span>
            <div>
                <p class="font-semibold text-[#1b1c1c]">Verifikasi Izin</p>
                <p class="text-xs text-[#717a6d] mt-0.5">{{ $pendingIzin ?? 0 }} pengajuan menunggu</p>
            </div>
        </a>
    </div>
</x-app-layout>
