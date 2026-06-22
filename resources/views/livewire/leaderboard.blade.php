<div>
    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <div class="flex gap-1 p-1 rounded-xl" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
            @foreach(['minggu' => 'Minggu Ini', 'bulan' => 'Bulan Ini', 'semester' => 'Semester', 'all' => 'Tahun Ini'] as $key => $label)
                <button wire:click="$set('periode', '{{ $key }}')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                               {{ $periode === $key ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <select wire:model.live="kelasId" class="form-select w-auto">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
    </div>

    {{-- Loading state --}}
    <div wire:loading class="text-center py-8 text-indigo-400">
        <div class="inline-flex items-center gap-3">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Memuat peringkat...
        </div>
    </div>

    {{-- Rankings --}}
    <div class="space-y-3" wire:loading.remove>

        @forelse($rankings as $item)
            @php
                $rankClass = match($item['rank']) { 1 => 'rank-1', 2 => 'rank-2', 3 => 'rank-3', default => '' };
                $medalIcon = match($item['rank']) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => null };
            @endphp

            <div class="flex items-center gap-4 p-4 rounded-2xl border border-white/8 transition-all duration-200 hover:-translate-y-0.5 {{ $rankClass }}"
                 style="{{ !$rankClass ? 'background: rgba(255,255,255,0.02)' : '' }}">

                {{-- Rank --}}
                <div class="w-10 text-center flex-shrink-0">
                    @if($medalIcon)
                        <span class="text-2xl">{{ $medalIcon }}</span>
                    @else
                        <span class="text-lg font-bold text-gray-500">#{{ $item['rank'] }}</span>
                    @endif
                </div>

                {{-- Avatar --}}
                <img src="{{ $item['student']['foto'] ? asset('storage/'.$item['student']['foto']) : 'https://ui-avatars.com/api/?name='.urlencode(substr($item['student']['nama'],0,1)).'&background=4F46E5&color=fff&size=64' }}"
                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0" alt="">

                {{-- Name & Class --}}
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-white truncate">{{ $item['student']['nama'] }}</div>
                    <div class="text-xs text-gray-400">
                        {{ $item['student']['kelas']['nama_kelas'] ?? '-' }} •
                        {{ $item['hadir'] }} hari hadir dari {{ $item['total'] }}
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                    @if($item['is_perfect'])
                        <span class="badge-perfect">⭐ 100%</span>
                    @else
                        <span class="text-lg font-bold {{ $item['persentase'] >= 90 ? 'text-green-400' : ($item['persentase'] >= 75 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ $item['persentase'] }}%
                        </span>
                    @endif
                    <div class="progress-bar w-24">
                        <div class="progress-fill {{ $item['persentase'] >= 90 ? 'progress-fill-green' : '' }}"
                             style="width: {{ $item['persentase'] }}%"></div>
                    </div>
                </div>
            </div>

        @empty
            <div class="text-center py-12 text-gray-500">
                <div class="text-4xl mb-3">📊</div>
                <div>Belum ada data kehadiran untuk periode ini</div>
            </div>
        @endforelse

    </div>
</div>
