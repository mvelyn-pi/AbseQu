<x-app-layout>
    <x-slot name="title">Pengajuan Izin</x-slot>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#00450d]">Pengajuan Izin Siswa</h2>
            @if(($pendingCount ?? 0) > 0)
                <p class="text-sm text-[#E65100] mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size:16px">pending</span>
                    {{ $pendingCount }} pengajuan menunggu persetujuan
                </p>
            @else
                <p class="text-sm text-[#717a6d] mt-1">Kelola permohonan izin dan sakit siswa</p>
            @endif
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="status" class="border border-[#c0c9bb] rounded-lg text-sm px-3 py-2 focus:outline-none focus:border-[#1b5e20] bg-white appearance-none text-[#1b1c1c]">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#1b5e20] text-white text-sm font-semibold rounded-lg hover:bg-[#00450d] transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Tanggal Izin</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Alasan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $izin)
                    <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                        <td class="px-6 py-3">
                            <div class="font-semibold text-sm text-[#1b1c1c]">{{ $izin->student->nama }}</div>
                            <div class="text-xs text-[#717a6d]">{{ $izin->student->kelas->nama_kelas ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-3 text-sm font-medium text-[#1b1c1c]">
                            {{ $izin->tanggal_izin instanceof \Carbon\Carbon ? $izin->tanggal_izin->format('d M Y') : \Carbon\Carbon::parse($izin->tanggal_izin)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3">
                            @if($izin->jenis === 'Sakit')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#E3F2FD] text-[#1565C0]">Sakit</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#e5e2e1] text-[#41493e]">Izin</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-[#717a6d] max-w-xs truncate">{{ $izin->alasan }}</td>
                        <td class="px-6 py-3">
                            @if($izin->status === 'Pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FBC02D] text-[#3E2723]">Pending</span>
                            @elseif($izin->status === 'Approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#4CAF50] text-white">Disetujui</span>
                            @elseif($izin->status === 'Rejected')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#D32F2F] text-white">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($izin->status === 'Pending')
                                <div class="flex gap-2 justify-center">
                                    <button onclick="showApproveModal('{{ $izin->id }}')" class="px-3 py-1 bg-[#4CAF50] text-white text-xs font-semibold rounded-lg hover:bg-[#2E7D32] transition-colors">
                                        ✓ Setuju
                                    </button>
                                    <button onclick="showRejectModal('{{ $izin->id }}')" class="px-3 py-1 bg-[#D32F2F] text-white text-xs font-semibold rounded-lg hover:bg-[#B71C1C] transition-colors">
                                        ✕ Tolak
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-[#717a6d] block text-center">
                                    {{ $izin->diproses_at ? \Carbon\Carbon::parse($izin->diproses_at)->format('d M Y') : '-' }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    {{-- Approve form (hidden by default) --}}
                    <tr id="approve-row-{{ $izin->id }}" class="hidden bg-[#E8F5E9] border-b border-[#c0c9bb]">
                        <td colspan="6" class="px-6 py-4">
                            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.izin.approve' : 'guru.izin.approve', $izin) }}" class="flex gap-3 items-end">
                                @csrf @method('PATCH')
                                <div class="flex-1">
                                    <label class="text-xs font-semibold text-[#2E7D32] block mb-1">Catatan Persetujuan (Opsional)</label>
                                    <textarea name="catatan_guru" rows="2" placeholder="Berikan catatan persetujuan jika ada..."
                                        class="w-full px-3 py-2 border border-[#4CAF50]/30 rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#4CAF50] resize-none"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#4CAF50] text-white text-sm font-semibold rounded-lg hover:bg-[#2E7D32] transition-colors">Setuju</button>
                                    <button type="button" onclick="hideApproveModal('{{ $izin->id }}')" class="px-4 py-2 border border-[#c0c9bb] text-[#717a6d] text-sm font-semibold rounded-lg hover:bg-[#f6f3f2] transition-colors">Batal</button>
                                </div>
                            </form>
                        </td>
                    </tr>

                    {{-- Reject form (hidden by default) --}}
                    <tr id="reject-row-{{ $izin->id }}" class="hidden bg-[#ffdad6] border-b border-[#c0c9bb]">
                        <td colspan="6" class="px-6 py-4">
                            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.izin.reject' : 'guru.izin.reject', $izin) }}" class="flex gap-3 items-end">
                                @csrf @method('PATCH')
                                <div class="flex-1">
                                    <label class="text-xs font-semibold text-[#D32F2F] block mb-1">Alasan Penolakan *</label>
                                    <textarea name="catatan_guru" rows="2" required placeholder="Berikan alasan penolakan..."
                                        class="w-full px-3 py-2 border border-[#ba1a1a]/30 rounded-lg text-sm text-[#1b1c1c] bg-white focus:outline-none focus:border-[#D32F2F] resize-none"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-4 py-2 bg-[#D32F2F] text-white text-sm font-semibold rounded-lg hover:bg-[#B71C1C] transition-colors">Tolak</button>
                                    <button type="button" onclick="hideRejectModal('{{ $izin->id }}')" class="px-4 py-2 border border-[#c0c9bb] text-[#717a6d] text-sm font-semibold rounded-lg hover:bg-[#f6f3f2] transition-colors">Batal</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#717a6d]">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-50">article</span>
                            Tidak ada pengajuan izin
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaveRequests->hasPages())
        <div class="px-6 py-4 border-t border-[#c0c9bb] bg-[#f6f3f2]">
            {{ $leaveRequests->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <x-slot name="scripts">
    <script>
        function showApproveModal(id) {
            document.getElementById('approve-row-' + id).classList.remove('hidden');
            document.getElementById('reject-row-' + id).classList.add('hidden');
        }
        function hideApproveModal(id) {
            document.getElementById('approve-row-' + id).classList.add('hidden');
        }
        function showRejectModal(id) {
            document.getElementById('reject-row-' + id).classList.remove('hidden');
            document.getElementById('approve-row-' + id).classList.add('hidden');
        }
        function hideRejectModal(id) {
            document.getElementById('reject-row-' + id).classList.add('hidden');
        }
    </script>
    </x-slot>
</x-app-layout>
