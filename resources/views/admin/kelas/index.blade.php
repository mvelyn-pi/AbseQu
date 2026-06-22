<x-app-layout>
    <x-slot name="title">Data Kelas</x-slot>

    <div class="max-w-7xl mx-auto space-y-lg">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Data Kelas</h2>
                <p class="text-on-surface-variant font-body-lg text-body-lg max-w-2xl">Kelola informasi kelas, wali kelas, dan distribusi siswa untuk tahun ajaran aktif.</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center gap-xs bg-primary text-on-primary px-lg py-sm rounded-lg font-button-text text-button-text hover:bg-primary-container active:scale-95 shadow-none transition-colors">
                <span class="material-symbols-outlined">add</span>
                Tambah Kelas
            </a>
        </div>

        @if(session('success'))
            <div class="p-sm rounded-lg bg-secondary-container border border-secondary flex items-center gap-sm mb-lg">
                <span class="material-symbols-outlined text-secondary">check_circle</span>
                <span class="text-body-sm text-on-secondary-container font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-xl">
            <div class="bg-surface rounded-xl border border-outline-variant p-lg flex items-center gap-md">
                <div class="p-sm bg-secondary-container rounded-lg text-on-secondary-container">
                    <span class="material-symbols-outlined">class</span>
                </div>
                <div>
                    <p class="text-on-surface-variant font-label-bold text-label-bold">Total Kelas</p>
                    <p class="font-headline-md text-headline-md text-on-surface">{{ $kelas->count() }}</p>
                </div>
            </div>
            <div class="bg-surface rounded-xl border border-outline-variant p-lg flex items-center gap-md">
                <div class="p-sm bg-primary-container/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div>
                    <p class="text-on-surface-variant font-label-bold text-label-bold">Total Siswa</p>
                    <p class="font-headline-md text-headline-md text-on-surface">{{ collect($kelas)->sum(fn($k) => $k->students->count()) }}</p>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden">
            <div class="px-lg py-md bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md text-on-surface">Daftar Kelas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-lowest">
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant border-b border-outline-variant w-16">No</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant border-b border-outline-variant">Nama Kelas</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant border-b border-outline-variant">Wali Kelas</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant border-b border-outline-variant">Jumlah Siswa</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant border-b border-outline-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-sm text-body-sm text-on-surface">
                        @forelse($kelas as $i => $k)
                        <tr class="hover:bg-surface-container-low transition-colors duration-150 group">
                            <td class="px-lg py-md border-b border-outline-variant text-on-surface-variant">{{ $i + 1 }}</td>
                            <td class="px-lg py-md border-b border-outline-variant font-bold">{{ $k->tingkat }} - {{ $k->nama_kelas }}</td>
                            <td class="px-lg py-md border-b border-outline-variant">{{ $k->waliKelas->nama ?? 'Belum ada' }}</td>
                            <td class="px-lg py-md border-b border-outline-variant">
                                <span class="inline-flex items-center px-sm py-xs rounded-full bg-secondary-container text-on-secondary-container font-label-bold text-label-bold">
                                    {{ $k->students->count() }} Siswa
                                </span>
                            </td>
                            <td class="px-lg py-md border-b border-outline-variant text-right">
                                <div class="flex justify-end gap-xs transition-opacity">
                                    <a href="{{ route('admin.kelas.edit', $k) }}" class="p-xs border border-outline-variant rounded-lg text-primary hover:bg-primary-fixed duration-150">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kelas ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-xs border border-outline-variant rounded-lg text-error hover:bg-error-container duration-150">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-lg py-md border-b border-outline-variant text-center text-on-surface-variant">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
