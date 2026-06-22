@extends('layouts.orangtua')
@section('title', 'Riwayat Kehadiran — AbsenQu')
@section('back_route', route('orangtua.dashboard'))

@section('content')
<div class="max-w-[1200px] mx-auto p-md md:p-container-margin">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-lg gap-md">
        <div>
            <h2 class="text-headline-lg-mobile md:text-headline-lg text-on-background font-bold mb-1">Riwayat Kehadiran</h2>
            <p class="text-body-lg text-on-surface-variant">{{ $child->nama }} — {{ $child->kelas?->nama_kelas ?? '-' }}</p>
        </div>
        <form method="GET" class="w-full md:w-auto">
            <input type="month" name="bulan" value="{{ $bulan }}" 
                class="w-full md:w-auto px-md py-sm border border-outline-variant rounded-lg bg-surface text-body-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                onchange="this.form.submit()">
        </form>
    </div>

    {{-- History Table --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-label-bold text-on-surface-variant uppercase tracking-wider bg-surface-container-low border-b border-outline-variant">
                        <th class="px-md py-sm font-semibold">Tanggal</th>
                        <th class="px-md py-sm font-semibold">Hari</th>
                        <th class="px-md py-sm font-semibold">Waktu Scan</th>
                        <th class="px-md py-sm font-semibold">Status</th>
                        <th class="px-md py-sm font-semibold">Keterlambatan</th>
                        <th class="px-md py-sm font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-md py-sm text-body-sm text-on-surface font-semibold">{{ $att->tanggal->format('d M Y') }}</td>
                            <td class="px-md py-sm text-body-sm text-on-surface-variant">{{ $att->tanggal->translatedFormat('l') }}</td>
                            <td class="px-md py-sm text-body-sm text-on-surface font-mono">{{ $att->waktu_scan ? \Carbon\Carbon::parse($att->waktu_scan)->format('H:i') : '—' }}</td>
                            <td class="px-md py-sm">
                                @if($att->status === 'Hadir')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#4CAF50] text-white">HADIR</span>
                                @elseif($att->status === 'Terlambat')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#FBC02D] text-[#3E2723]">TERLAMBAT</span>
                                @elseif($att->status === 'Izin')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white">IZIN</span>
                                @elseif($att->status === 'Sakit')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white">SAKIT</span>
                                @elseif($att->status === 'Alpha')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#D32F2F] text-white">ALPHA</span>
                                @endif
                            </td>
                            <td class="px-md py-sm text-body-sm text-on-surface">{{ $att->menit_terlambat > 0 ? $att->menit_terlambat . ' menit' : '—' }}</td>
                            <td class="px-md py-sm text-body-sm text-on-surface-variant">{{ $att->keterangan ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-xl text-on-surface-variant text-body-sm">
                                Tidak ada data kehadiran pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
