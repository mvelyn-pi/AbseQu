@extends('layouts.orangtua')
@section('title', 'Riwayat Pengajuan Izin — AbsenQu')
@section('back_route', route('orangtua.dashboard'))

@section('content')
<div class="max-w-[1200px] mx-auto p-md md:p-container-margin">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-lg gap-md">
        <div>
            <h2 class="text-headline-lg-mobile md:text-headline-lg text-on-background font-bold mb-1">Pengajuan Izin</h2>
            <p class="text-body-lg text-on-surface-variant">Riwayat pengajuan izin dan sakit siswa.</p>
        </div>
        <a href="{{ route('orangtua.izin.create') }}" class="w-full md:w-auto bg-primary-container text-on-primary rounded-lg px-md py-sm text-button-text font-semibold flex items-center justify-center gap-xs hover:bg-primary transition-colors">
            <span class="material-symbols-outlined" style="font-size:18px">add</span>
            Ajukan Izin Baru
        </a>
    </div>

    {{-- History Table --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-label-bold text-on-surface-variant uppercase tracking-wider bg-surface-container-low border-b border-outline-variant">
                        <th class="px-md py-sm font-semibold">Tanggal</th>
                        <th class="px-md py-sm font-semibold">Jenis</th>
                        <th class="px-md py-sm font-semibold">Alasan & Bukti</th>
                        <th class="px-md py-sm font-semibold">Status</th>
                        <th class="px-md py-sm font-semibold">Catatan Guru</th>
                        <th class="px-md py-sm font-semibold text-right">Diajukan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $izin)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-md py-sm text-body-sm text-on-surface font-semibold">{{ $izin->tanggal_izin->format('d M Y') }}</td>
                            <td class="px-md py-sm">
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white uppercase">{{ $izin->jenis }}</span>
                            </td>
                            <td class="px-md py-sm max-w-xs">
                                <div class="text-body-sm text-on-surface truncate mb-1">{{ $izin->alasan }}</div>
                                @if($izin->bukti)
                                    <a href="{{ $izin->bukti_url }}" target="_blank" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                                        <span class="material-symbols-outlined" style="font-size:14px">attachment</span>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs text-outline italic">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td class="px-md py-sm">
                                @if($izin->status === 'Pending')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#FBC02D] text-[#3E2723]">PENDING</span>
                                @elseif($izin->status === 'Approved')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#4CAF50] text-white">DISETUJUI</span>
                                @elseif($izin->status === 'Rejected')
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#D32F2F] text-white">DITOLAK</span>
                                @endif
                            </td>
                            <td class="px-md py-sm text-body-sm text-on-surface-variant">{{ $izin->catatan_guru ?? '—' }}</td>
                            <td class="px-md py-sm text-xs text-on-surface-variant text-right">{{ $izin->created_at->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-xl text-on-surface-variant text-body-sm">
                                Belum ada riwayat pengajuan izin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($leaves->hasPages())
        <div class="px-lg py-md border-t border-outline-variant bg-surface-container-low">
            {{ $leaves->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
