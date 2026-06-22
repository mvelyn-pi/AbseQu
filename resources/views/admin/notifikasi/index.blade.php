@extends('layouts.app')
@section('title', 'Log Notifikasi WhatsApp')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-[#00450d]">Log Notifikasi WhatsApp</h2>
    <p class="text-sm text-[#717a6d] mt-1">Riwayat pengiriman pesan WhatsApp ke orang tua siswa</p>
</div>

{{-- Stats + Action --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
        <div class="p-2 rounded-lg bg-[#4CAF50]/10 flex-shrink-0">
            <span class="material-symbols-outlined text-[#2E7D32]" style="font-size:22px">check_circle</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Berhasil Hari Ini</p>
            <p class="text-2xl font-bold text-[#2E7D32]">{{ $todaySuccess ?? 0 }}</p>
        </div>
    </div>
    <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
        <div class="p-2 rounded-lg bg-[#D32F2F]/10 flex-shrink-0">
            <span class="material-symbols-outlined text-[#D32F2F]" style="font-size:22px">cancel</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Gagal Hari Ini</p>
            <p class="text-2xl font-bold text-[#D32F2F]">{{ $todayFailed ?? 0 }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.notifikasi.clear') }}" class="w-1/3" onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat log notifikasi?');">
            @csrf
            @method('DELETE')
            <button type="submit" title="Hapus Log" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#D32F2F] text-white text-sm font-semibold rounded-xl hover:bg-[#B71C1C] transition-colors shadow-sm">
                <span class="material-symbols-outlined" style="font-size:20px">delete</span>
                <span class="hidden md:inline">Hapus Log</span>
            </button>
        </form>
        <form method="POST" action="{{ route('admin.absensi.notify') }}" class="w-2/3">
            @csrf
            <button type="submit" title="Kirim Notif Alpha Sekarang" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#25D366] text-white text-sm font-semibold rounded-xl hover:bg-[#128C7E] transition-colors shadow-sm">
                <span class="material-symbols-outlined" style="font-size:20px">send</span>
                <span class="hidden md:inline">Kirim Notif Alpha Sekarang</span>
            </button>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                    <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">No. WA</th>
                    <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Pesan/Error</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                    <td class="px-6 py-3">
                        <div class="font-semibold text-sm text-[#1b1c1c]">{{ $log->student->nama }}</div>
                        <div class="text-xs text-[#717a6d]">{{ $log->student->kelas->nama_kelas ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-3 text-sm text-[#1b1c1c]">
                        {{ $log->tanggal_kirim instanceof \Carbon\Carbon ? $log->tanggal_kirim->format('d M Y') : \Carbon\Carbon::parse($log->tanggal_kirim)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-3 font-mono text-sm text-[#717a6d]">{{ $log->no_tujuan }}</td>
                    <td class="px-6 py-3">
                        @if($log->status_kirim === 'success')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#4CAF50] text-white">Berhasil</span>
                        @elseif($log->status_kirim === 'failed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#D32F2F] text-white">Gagal</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FBC02D] text-[#3E2723]">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-xs text-[#717a6d] max-w-xs truncate">
                        {{ $log->status_kirim === 'failed' ? $log->error_message : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-[#717a6d]">
                        <span class="material-symbols-outlined text-5xl block mb-2 opacity-50">notifications_off</span>
                        Belum ada log notifikasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-[#c0c9bb] bg-[#f6f3f2]">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
