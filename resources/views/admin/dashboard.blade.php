<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <!-- Header -->
    <div class="mb-lg">
        <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-base">Dashboard</h2>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Overview kehadiran harian — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md mb-xl">
        <!-- Hadir -->
        <div class="bg-surface border border-outline-variant rounded-xl p-md flex items-start gap-sm">
            <div class="p-xs bg-[#4CAF50]/10 rounded-lg text-[#1B5E20]">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
            <div>
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider mb-base">Hadir Hari Ini</p>
                <p class="font-headline-md text-headline-md text-on-surface">{{ $stats['hadir'] ?? 0 }}</p>
            </div>
        </div>
        <!-- Terlambat -->
        <div class="bg-surface border border-outline-variant rounded-xl p-md flex items-start gap-sm">
            <div class="p-xs bg-[#FBC02D]/10 rounded-lg text-[#FBC02D]">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            <div>
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider mb-base">Terlambat</p>
                <p class="font-headline-md text-headline-md text-on-surface">{{ $stats['terlambat'] ?? 0 }}</p>
            </div>
        </div>
        <!-- Alpha -->
        <div class="bg-surface border border-outline-variant rounded-xl p-md flex items-start gap-sm">
            <div class="p-xs bg-[#D32F2F]/10 rounded-lg text-[#D32F2F]">
                <span class="material-symbols-outlined">person_off</span>
            </div>
            <div>
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider mb-base">Alpha</p>
                <p class="font-headline-md text-headline-md text-on-surface">{{ $stats['alpha'] ?? 0 }}</p>
            </div>
        </div>
        <!-- Total Siswa -->
        <div class="bg-surface border border-outline-variant rounded-xl p-md flex items-start gap-sm">
            <div class="p-xs bg-primary-container/10 rounded-lg text-primary-container">
                <span class="material-symbols-outlined">groups</span>
            </div>
            <div>
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider mb-base">Total Siswa</p>
                <p class="font-headline-md text-headline-md text-on-surface">{{ $stats['total_siswa'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Attendance Table Card -->
    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
        <div class="bg-[#F1F8E9] px-md py-sm border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-headline-md text-headline-md text-on-surface">Data Kehadiran Hari Ini</h3>
            <a href="{{ route('admin.absensi.index') }}" class="text-primary hover:text-secondary font-button-text text-button-text flex items-center gap-xs">
                View All
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="font-label-bold text-label-bold text-on-surface-variant px-md py-sm">No</th>
                        <th class="font-label-bold text-label-bold text-on-surface-variant px-md py-sm">Nama</th>
                        <th class="font-label-bold text-label-bold text-on-surface-variant px-md py-sm">Kelas</th>
                        <th class="font-label-bold text-label-bold text-on-surface-variant px-md py-sm">Jam Scan</th>
                        <th class="font-label-bold text-label-bold text-on-surface-variant px-md py-sm">Status</th>
                    </tr>
                </thead>
                <tbody class="font-body-sm text-body-sm text-on-surface">
                    @forelse($todayAttendances as $i => $att)
                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                        <td class="px-md py-sm">{{ $i + 1 }}</td>
                        <td class="px-md py-sm font-medium">{{ $att->student->nama ?? '-' }}</td>
                        <td class="px-md py-sm text-on-surface-variant">{{ $att->student->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-md py-sm text-on-surface-variant font-mono">{{ $att->waktu_scan ? \Carbon\Carbon::parse($att->waktu_scan)->format('H:i') : '-' }}</td>
                        <td class="px-md py-sm">
                            @if($att->status === 'Hadir')
                                <span class="inline-block px-xs py-[2px] bg-[#4CAF50] text-[#002204] rounded-full font-label-bold text-label-bold">Hadir</span>
                            @elseif($att->status === 'Terlambat')
                                <span class="inline-block px-xs py-[2px] bg-[#FBC02D] text-[#3E2723] rounded-full font-label-bold text-label-bold">Terlambat</span>
                            @elseif($att->status === 'Alpha')
                                <span class="inline-block px-xs py-[2px] bg-[#D32F2F] text-white rounded-full font-label-bold text-label-bold">Alpha</span>
                            @elseif($att->status === 'Izin')
                                <span class="inline-block px-xs py-[2px] bg-surface-container-high text-on-surface-variant rounded-full font-label-bold text-label-bold border border-outline-variant">Izin</span>
                            @elseif($att->status === 'Sakit')
                                <span class="inline-block px-xs py-[2px] bg-[#E3F2FD] text-[#1565C0] rounded-full font-label-bold text-label-bold">Sakit</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-xl text-on-surface-variant">
                            <span class="material-symbols-outlined block text-5xl mb-sm opacity-40">event_busy</span>
                            <p class="font-body-lg text-body-lg">Belum ada data kehadiran hari ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
