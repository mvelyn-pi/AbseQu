<x-app-layout>
    <x-slot name="title">Rekap Absensi</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#00450d]">Rekap Absensi</h2>
        <p class="text-sm text-[#717a6d] mt-1">Data kehadiran siswa berdasarkan tanggal dan kelas</p>
    </div>

    {{-- Filters --}}
    <section class="bg-white border border-[#c0c9bb] rounded-xl p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Tanggal</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 border border-[#c0c9bb] rounded-lg bg-white">
                        <span class="material-symbols-outlined text-[#717a6d]" style="font-size:18px">calendar_today</span>
                        <input type="date" name="tanggal" value="{{ $tanggal ?? today()->format('Y-m-d') }}"
                            max="{{ today()->format('Y-m-d') }}"
                            class="text-sm w-full border-none focus:ring-0 p-0 bg-transparent text-[#1b1c1c]">
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#41493e] uppercase tracking-wider">Kelas</label>
                    <select name="kelas_id" class="w-full px-3 py-2.5 border border-[#c0c9bb] rounded-lg bg-white text-sm text-[#1b1c1c] focus:border-[#1b5e20] focus:ring-0 outline-none appearance-none">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList ?? [] as $k)
                            <option value="{{ $k->id }}" {{ ($kelasId ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex items-center gap-1 px-4 py-2.5 bg-[#1b5e20] text-white text-sm font-semibold rounded-lg hover:bg-[#00450d] transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">filter_list</span>
                    Filter
                </button>
                <a href="{{ request()->routeIs('admin.*') ? route('admin.absensi.index') : route('guru.absensi.index') }}"
                    class="flex items-center gap-1 px-4 py-2.5 bg-transparent border border-[#c0c9bb] text-sm font-semibold text-[#1b5e20] rounded-lg hover:bg-[#f6f3f2] transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">refresh</span>
                    Reset
                </a>
                <a href="{{ request()->routeIs('admin.*') ? route('admin.absensi.export', request()->query()) : route('guru.absensi.export', request()->query()) }}"
                    class="flex items-center gap-1 px-4 py-2.5 bg-[#E3F2FD] border border-[#2196F3] text-sm font-semibold text-[#1565C0] rounded-lg hover:bg-[#BBDEFB] transition-colors" title="Export CSV">
                    <span class="material-symbols-outlined" style="font-size:18px">download</span>
                    Export
                </a>
            </div>
        </form>
    </section>

    {{-- Summary Cards --}}
    <section class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="bg-white border-l-4 border-l-[#4CAF50] border border-[#c0c9bb] rounded-xl p-4 text-center">
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Hadir</p>
            <p class="text-2xl font-bold text-[#2E7D32]">{{ $summary['hadir'] ?? 0 }}</p>
        </div>
        <div class="bg-white border-l-4 border-l-[#FBC02D] border border-[#c0c9bb] rounded-xl p-4 text-center">
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Terlambat</p>
            <p class="text-2xl font-bold text-[#E65100]">{{ $summary['terlambat'] ?? 0 }}</p>
        </div>
        <div class="bg-white border-l-4 border-l-[#2196F3] border border-[#c0c9bb] rounded-xl p-4 text-center">
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Izin</p>
            <p class="text-2xl font-bold text-[#1565C0]">{{ $summary['izin'] ?? 0 }}</p>
        </div>
        <div class="bg-white border-l-4 border-l-[#9C27B0] border border-[#c0c9bb] rounded-xl p-4 text-center">
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Sakit</p>
            <p class="text-2xl font-bold text-[#6A1B9A]">{{ $summary['sakit'] ?? 0 }}</p>
        </div>
        <div class="bg-white border-l-4 border-l-[#D32F2F] border border-[#c0c9bb] rounded-xl p-4 text-center">
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Alpha</p>
            <p class="text-2xl font-bold text-[#D32F2F]">{{ $summary['alpha'] ?? 0 }}</p>
        </div>
    </section>

    {{-- Main Table --}}
    <section class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2] flex justify-between items-center">
            <h4 class="font-semibold text-[#1b1c1c]">Data Kehadiran Siswa</h4>
            <span class="text-xs font-semibold text-[#717a6d] bg-white border border-[#c0c9bb] px-3 py-1 rounded-full">
                {{ $attendances->total() ?? 0 }} siswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider text-center">Waktu Scan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider text-center">Terlambat</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#1b5e20]/10 flex items-center justify-center font-bold text-[#1b5e20] text-xs flex-shrink-0">
                                    {{ strtoupper(substr($att->student->nama ?? 'S', 0, 2)) }}
                                </div>
                                <span class="text-sm font-semibold text-[#1b1c1c]">{{ $att->student->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $att->student->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-3 text-center font-mono text-sm text-[#1b1c1c]">{{ $att->waktu_scan ? \Carbon\Carbon::parse($att->waktu_scan)->format('H:i') : '—' }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($att->status === 'Hadir')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#4CAF50] text-white">Hadir</span>
                            @elseif($att->status === 'Terlambat')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FBC02D] text-[#3E2723]">Terlambat</span>
                            @elseif($att->status === 'Alpha')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#D32F2F] text-white">Alpha</span>
                            @elseif($att->status === 'Izin')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#e5e2e1] text-[#41493e] border border-[#c0c9bb]">Izin</span>
                            @elseif($att->status === 'Sakit')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#E3F2FD] text-[#1565C0]">Sakit</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center text-sm text-[#717a6d]">{{ ($att->menit_terlambat ?? 0) > 0 ? $att->menit_terlambat . ' mnt' : '—' }}</td>
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $att->keterangan ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#717a6d]">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-50">event_busy</span>
                            Tidak ada data absensi untuk filter ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($attendances) && $attendances->hasPages())
        <div class="px-6 py-4 border-t border-[#c0c9bb] flex items-center justify-between bg-[#f6f3f2]">
            <p class="text-xs text-[#717a6d]">Menampilkan {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }} dari {{ $attendances->total() }} entri</p>
            {{ $attendances->withQueryString()->links() }}
        </div>
        @endif
    </section>
</x-app-layout>
