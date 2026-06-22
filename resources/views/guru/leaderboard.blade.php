<x-app-layout>
    <x-slot name="title">Leaderboard Kedisiplinan</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#00450d]">Leaderboard Kedisiplinan</h2>
        <p class="text-sm text-[#717a6d] mt-1">Peringkat kehadiran siswa berprestasi bulan ini</p>
    </div>

    {{-- Podium Top 3 --}}
    @if(count($leaderboard ?? []) >= 3)
    <section class="mb-8 pt-4 pb-6 flex justify-center items-end gap-6">
        <div class="flex flex-col items-center justify-end">
            <div class="w-14 h-14 rounded-full border-2 border-[#c0c9bb] bg-[#f6f3f2] flex items-center justify-center font-bold text-[#1b1c1c] text-sm mb-2">
                {{ strtoupper(substr($leaderboard[1]['student']->nama ?? 'S', 0, 2)) }}
            </div>
            <div class="text-center">
                <p class="text-xs font-semibold text-[#717a6d] bg-[#e5e2e1] rounded px-2 py-0.5 mb-1">#2</p>
                <h3 class="text-sm font-semibold text-[#1b1c1c]">{{ Str::limit($leaderboard[1]['student']->nama ?? '-', 10) }}</h3>
                <p class="text-xs text-[#717a6d]">{{ $leaderboard[1]['student']->kelas->nama_kelas ?? '-' }}</p>
                <p class="text-sm font-bold text-[#006e1c]">{{ $leaderboard[1]['persentase'] ?? 0 }}%</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-end -translate-y-6">
            <span class="material-symbols-outlined text-4xl mb-1" style="color: #FBC02D; font-variation-settings:'FILL' 1">military_tech</span>
            <div class="w-20 h-20 rounded-full border-2 border-[#1b5e20] bg-[#1b5e20]/10 flex items-center justify-center font-bold text-[#1b5e20] text-xl mb-2">
                {{ strtoupper(substr($leaderboard[0]['student']->nama ?? 'S', 0, 2)) }}
            </div>
            <div class="text-center">
                <p class="text-xs font-bold text-[#1b5e20] bg-[#1b5e20]/10 rounded px-2 py-0.5 mb-1">#1 Terbaik</p>
                <h3 class="text-base font-bold text-[#00450d]">{{ Str::limit($leaderboard[0]['student']->nama ?? '-', 12) }}</h3>
                <p class="text-xs text-[#717a6d]">{{ $leaderboard[0]['student']->kelas->nama_kelas ?? '-' }}</p>
                <p class="text-sm font-bold text-[#00450d]">{{ $leaderboard[0]['persentase'] ?? 0 }}%</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-end">
            <div class="w-14 h-14 rounded-full border-2 border-[#c0c9bb] bg-[#f6f3f2] flex items-center justify-center font-bold text-[#1b1c1c] text-sm mb-2">
                {{ strtoupper(substr($leaderboard[2]['student']->nama ?? 'S', 0, 2)) }}
            </div>
            <div class="text-center">
                <p class="text-xs font-semibold text-[#717a6d] bg-[#e5e2e1] rounded px-2 py-0.5 mb-1">#3</p>
                <h3 class="text-sm font-semibold text-[#1b1c1c]">{{ Str::limit($leaderboard[2]['student']->nama ?? '-', 10) }}</h3>
                <p class="text-xs text-[#717a6d]">{{ $leaderboard[2]['student']->kelas->nama_kelas ?? '-' }}</p>
                <p class="text-sm font-bold text-[#006e1c]">{{ $leaderboard[2]['persentase'] ?? 0 }}%</p>
            </div>
        </div>
    </section>
    @endif

    {{-- Full Table --}}
    <section class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2]">
            <h4 class="font-semibold text-[#1b1c1c]">Peringkat Lengkap</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider w-16 text-center">Rank</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Persentase</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard ?? [] as $i => $item)
                    @php $student = $item['student']; @endphp
                    <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                        <td class="px-6 py-3 text-center font-bold {{ $i === 0 ? 'text-[#00450d]' : 'text-[#717a6d]' }}">{{ $item['rank'] }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#1b5e20]/10 flex items-center justify-center font-semibold text-[#1b5e20] text-xs flex-shrink-0">
                                    {{ strtoupper(substr($student->nama, 0, 2)) }}
                                </div>
                                <span class="text-sm font-semibold text-[#1b1c1c]">{{ $student->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $student->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-[#1b1c1c]">{{ $item['hadir'] }} / {{ $item['total'] }} Hari</td>
                        <td class="px-6 py-3 text-sm font-bold {{ $item['persentase'] >= 90 ? 'text-[#2E7D32]' : ($item['persentase'] >= 75 ? 'text-[#E65100]' : 'text-[#D32F2F]') }}">
                            {{ number_format($item['persentase'], 1) }}%
                        </td>
                        <td class="px-6 py-3">
                            @if($item['is_perfect'])
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold text-[#00731e] border border-[#91f78e] bg-[#F1F8E9]">
                                    <span class="material-symbols-outlined" style="font-size:12px">stars</span> Sempurna
                                </span>
                            @elseif($item['persentase'] >= 90)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#4CAF50] text-white">Baik</span>
                            @elseif($item['persentase'] >= 75)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FBC02D] text-[#3E2723]">Cukup</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#D32F2F] text-white">Perlu Perhatian</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#717a6d]">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-50">leaderboard</span>
                            Belum ada data leaderboard
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
