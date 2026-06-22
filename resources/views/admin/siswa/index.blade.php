<x-app-layout>
    <x-slot name="title">Data Siswa</x-slot>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#00450d]">Data Siswa</h2>
            <p class="text-sm text-[#717a6d] mt-1">Kelola data siswa, QR Code, dan ID Card digital</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1b5e20] text-white text-sm font-semibold rounded-lg hover:bg-[#00450d] transition-colors">
            <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
            Tambah Siswa
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#006e1c]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#006e1c]" style="font-size:22px">groups</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Total Siswa</p>
                <p class="text-2xl font-bold text-[#1b1c1c]">{{ $students->total() }}</p>
            </div>
        </div>
        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#1b5e20]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#1b5e20]" style="font-size:22px">class</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">Jumlah Kelas</p>
                <p class="text-2xl font-bold text-[#1b1c1c]">{{ $kelasList->count() }}</p>
            </div>
        </div>
        <div class="bg-white border border-[#c0c9bb] rounded-xl p-4 flex items-start gap-3">
            <div class="p-2 rounded-lg bg-[#4CAF50]/10 flex-shrink-0">
                <span class="material-symbols-outlined text-[#2E7D32]" style="font-size:22px">qr_code</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#717a6d] uppercase tracking-wider mb-1">QR Aktif</p>
                <p class="text-2xl font-bold text-[#1b1c1c]">{{ $students->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-[#c0c9bb] rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-[#c0c9bb] bg-[#f6f3f2] flex flex-col md:flex-row md:items-center justify-between gap-3">
            <h3 class="font-semibold text-[#1b1c1c]">Daftar Siswa</h3>
            <form method="GET" class="flex gap-2 items-center flex-wrap">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#717a6d]" style="font-size:16px">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..."
                        class="pl-8 pr-3 py-2 border border-[#c0c9bb] rounded-lg text-sm text-[#1b1c1c] focus:outline-none focus:border-[#1b5e20] w-52 bg-white">
                </div>
                <select name="kelas_id" class="border border-[#c0c9bb] rounded-lg text-sm px-3 py-2 focus:outline-none focus:border-[#1b5e20] bg-white appearance-none text-[#1b1c1c]">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-2 border border-[#1b5e20] text-[#1b5e20] text-sm font-semibold rounded-lg hover:bg-[#F1F8E9] transition-colors">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#c0c9bb] bg-[#F1F8E9]">
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider w-10">No</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider">Orang Tua</th>
                        <th class="px-6 py-3 text-xs font-semibold text-[#717a6d] uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $i => $siswa)
                    <tr class="border-b border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors">
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $students->firstItem() + $i }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-[#1b5e20]/10 flex items-center justify-center font-bold text-[#1b5e20] text-xs flex-shrink-0">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <span class="text-sm font-semibold text-[#1b1c1c]">{{ $siswa->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 font-mono text-sm text-[#717a6d]">{{ $siswa->nis }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#1b5e20]/10 text-[#1b5e20] border border-[#1b5e20]/20">
                                {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-[#717a6d]">{{ $siswa->parentUser->name ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.siswa.qr', $siswa) }}" class="p-1.5 border border-[#c0c9bb] rounded-lg text-[#1b5e20] hover:bg-[#F1F8E9] transition-colors" title="Lihat QR">
                                    <span class="material-symbols-outlined" style="font-size:16px">qr_code</span>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="p-1.5 border border-[#c0c9bb] rounded-lg text-[#1b5e20] hover:bg-[#F1F8E9] transition-colors" title="Edit">
                                    <span class="material-symbols-outlined" style="font-size:16px">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" onsubmit="return confirm('Hapus siswa {{ $siswa->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 border border-[#c0c9bb] rounded-lg text-[#D32F2F] hover:bg-[#ffdad6] transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-[#717a6d]">
                            <span class="material-symbols-outlined text-5xl block mb-2 opacity-50">person_search</span>
                            Tidak ada data siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#c0c9bb] flex items-center justify-between bg-[#f6f3f2]">
            <p class="text-xs text-[#717a6d]">Menampilkan {{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }} siswa</p>
            {{ $students->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
