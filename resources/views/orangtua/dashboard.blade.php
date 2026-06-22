@extends('layouts.orangtua')

@section('content')
<div class="max-w-[1200px] mx-auto p-md md:p-container-margin">
    {{-- Header --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-lg gap-md">
        <div>
            <p class="text-body-lg text-on-surface-variant mb-base">Portal Orang Tua</p>
            <h1 class="text-headline-lg-mobile md:text-headline-lg text-on-background font-bold">
                Selamat datang, {{ Auth::user()->name }}
            </h1>
        </div>
        <a href="{{ route('orangtua.izin.create') }}"
            class="bg-primary-container text-on-primary rounded px-md py-sm text-button-text font-semibold flex items-center gap-xs hover:bg-primary transition-colors">
            <span class="material-symbols-outlined" style="font-size:18px">edit_document</span>
            Ajukan Izin
        </a>
    </header>

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
        {{-- Student Card --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg lg:col-span-1 flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-full border border-outline-variant p-base mb-md bg-surface flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:48px">person</span>
            </div>
            <h2 class="text-headline-md text-on-background font-bold mb-base">{{ $child->nama ?? 'Nama Siswa' }}</h2>
            <p class="text-body-sm text-on-surface-variant bg-surface-container-low px-sm py-xs rounded border border-outline-variant">
                {{ $child->kelas?->nama_kelas ?? 'Kelas' }}
            </p>
            <div class="w-full h-px bg-outline-variant my-md"></div>
            <div class="w-full flex justify-between text-body-sm">
                <span class="text-on-surface-variant">NIS</span>
                <span class="font-semibold text-on-background">{{ $child->nis ?? '-' }}</span>
            </div>
            <div class="w-full flex justify-between text-body-sm mt-sm">
                <span class="text-on-surface-variant">Wali Kelas</span>
                <span class="font-semibold text-on-background">{{ $child->kelas?->guru?->name ?? '-' }}</span>
            </div>
        </div>

        {{-- Stats & Chart --}}
        <div class="lg:col-span-2 flex flex-col gap-gutter">
            {{-- Monthly Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col">
                    <span class="text-label-bold text-on-surface-variant uppercase tracking-wider mb-sm">Hadir</span>
                    <div class="flex items-end gap-sm">
                        <span class="text-headline-lg text-primary-container font-bold leading-none">{{ $summary['hadir'] ?? 0 }}</span>
                        <span class="text-body-sm text-on-surface-variant mb-1">Hari</span>
                    </div>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col">
                    <span class="text-label-bold text-on-surface-variant uppercase tracking-wider mb-sm">Terlambat</span>
                    <div class="flex items-end gap-sm">
                        <span class="text-headline-lg font-bold leading-none" style="color: #FBC02D">{{ $summary['terlambat'] ?? 0 }}</span>
                        <span class="text-body-sm text-on-surface-variant mb-1">Hari</span>
                    </div>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col">
                    <span class="text-label-bold text-on-surface-variant uppercase tracking-wider mb-sm">Izin/Sakit</span>
                    <div class="flex items-end gap-sm">
                        <span class="text-headline-lg text-secondary font-bold leading-none">{{ ($summary['izin'] ?? 0) + ($summary['sakit'] ?? 0) }}</span>
                        <span class="text-body-sm text-on-surface-variant mb-1">Hari</span>
                    </div>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col">
                    <span class="text-label-bold text-on-surface-variant uppercase tracking-wider mb-sm">Alpha</span>
                    <div class="flex items-end gap-sm">
                        <span class="text-headline-lg text-error font-bold leading-none">{{ $summary['alpha'] ?? 0 }}</span>
                        <span class="text-body-sm text-on-surface-variant mb-1">Hari</span>
                    </div>
                </div>
            </div>

            {{-- Week chart --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg flex-1">
                <div class="flex justify-between items-center border-b border-outline-variant pb-md mb-md">
                    <h3 class="text-headline-md text-on-background font-semibold">Tren Kehadiran Mingguan</h3>
                    <span class="text-body-sm text-on-surface-variant">7 Hari Terakhir</span>
                </div>
                <div class="h-48 w-full flex items-end justify-between gap-2 px-2 pt-4">
                    @foreach(collect($trend)->take(-7) as $day)
                    <div class="flex flex-col items-center gap-2 flex-1 group">
                        <div class="w-full rounded-t-sm transition-all"
                            style="height: {{ $day['status'] === 'Hadir' || $day['status'] === 'Terlambat' ? 100 : 20 }}%;
                                   background-color: {{ $day['status'] === 'Hadir' ? '#4CAF50' : ($day['status'] === 'Terlambat' ? '#FBC02D' : '#c0c9bb') }}">
                        </div>
                        <span class="text-label-bold text-on-surface-variant text-[10px]">{{ $day['date'] ?? '' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- History Table --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden mt-xl">
        <div class="bg-[#F1F8E9] px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="text-headline-md text-on-background font-semibold">Riwayat 7 Hari Terakhir</h3>
            <a href="{{ route('orangtua.absensi') }}" class="text-primary text-button-text font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-label-bold text-on-surface-variant uppercase tracking-wider bg-surface-container-low border-b border-outline-variant">
                        <th class="px-md py-sm font-semibold">Tanggal</th>
                        <th class="px-md py-sm font-semibold">Jam Masuk</th>
                        <th class="px-md py-sm font-semibold">Status</th>
                        <th class="px-md py-sm font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(collect($trend)->filter(fn($t) => $t['status'] !== 'N/A')->sortByDesc('date')->take(7) as $att)
                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                        <td class="px-md py-sm text-body-sm text-on-surface">{{ $att['date'] }}</td>
                        <td class="px-md py-sm text-body-sm text-on-surface font-mono">-</td>
                        <td class="px-md py-sm">
                            @if($att['status'] === 'Hadir')
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#4CAF50] text-white">HADIR</span>
                            @elseif($att['status'] === 'Terlambat')
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#FBC02D] text-[#3E2723]">TERLAMBAT</span>
                            @elseif($att['status'] === 'Izin')
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white">IZIN</span>
                            @elseif($att['status'] === 'Sakit')
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white">SAKIT</span>
                            @elseif($att['status'] === 'Alpha')
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#D32F2F] text-white">ALPHA</span>
                            @endif
                        </td>
                        <td class="px-md py-sm text-body-sm text-on-surface-variant">-</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-lg text-on-surface-variant text-body-sm">Belum ada data riwayat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
