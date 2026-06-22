<x-app-layout>
    <x-slot name="title">Data Orang Tua</x-slot>

    <div class="max-w-7xl mx-auto space-y-lg">
        <!-- Page Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Data Orang Tua</h2>
                <p class="font-body-lg text-on-surface-variant">Manajemen data akun wali murid / orang tua.</p>
            </div>
            <a href="{{ route('admin.orangtua.create') }}" class="inline-flex items-center gap-xs px-lg py-sm bg-primary text-on-primary font-button-text text-button-text rounded-lg hover:bg-primary-container transition-colors active:scale-95 duration-150">
                <span class="material-symbols-outlined" data-icon="add">add</span>
                Tambah Orang Tua
            </a>
        </div>

        @if(session('success'))
            <div class="p-sm rounded-lg bg-secondary-container border border-secondary flex items-center gap-sm mb-lg">
                <span class="material-symbols-outlined text-secondary">check_circle</span>
                <span class="text-body-sm text-on-secondary-container font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Bento Statistics Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <div class="bg-surface border border-outline-variant p-lg rounded-xl flex items-center gap-lg">
                <div class="w-12 h-12 bg-primary-container/10 flex items-center justify-center rounded-lg text-primary">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">family_restroom</span>
                </div>
                <div>
                    <p class="font-label-bold text-label-bold text-on-surface-variant uppercase">Total Orang Tua</p>
                    <p class="font-headline-md text-headline-md text-on-surface">{{ $orangtua->total() }} Orang</p>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
            <!-- Card Header/Filter Strip -->
            <div class="bg-surface-container-low px-lg py-md border-b border-outline-variant flex justify-between items-center">
                <form method="GET" action="{{ route('admin.orangtua.index') }}" class="flex items-center gap-md w-full max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data orang tua..." class="w-full border border-outline-variant rounded-lg px-sm py-xs font-body-sm text-body-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                    <button type="submit" class="flex items-center gap-xs px-md py-xs border border-outline-variant rounded-full font-label-bold text-label-bold text-on-surface hover:bg-surface-variant transition-colors">
                        Cari
                    </button>
                </form>
            </div>
            
            <!-- The Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider w-16 text-center">No</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Nama Orang Tua</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Email / Whatsapp</th>
                            <th class="px-lg py-md font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($orangtua as $i => $ortu)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md font-body-sm text-on-surface text-center">{{ $orangtua->firstItem() + $i }}</td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs">{{ substr($ortu->nama, 0, 2) }}</div>
                                    <div>
                                        <p class="font-label-bold text-on-surface">{{ $ortu->nama }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <p class="font-body-sm text-on-surface">{{ $ortu->email }}</p>
                                <p class="font-body-sm text-[10px] text-on-surface-variant">WA: {{ $ortu->no_whatsapp ?? '-' }}</p>
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex justify-end gap-xs transition-opacity">
                                    <a href="{{ route('admin.orangtua.edit', $ortu) }}" class="p-xs text-on-surface-variant hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" data-icon="edit">edit</span>
                                    </a>
                                    <form action="{{ route('admin.orangtua.destroy', $ortu) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data orang tua ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-xs text-on-surface-variant hover:text-error transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined" data-icon="delete">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-lg py-md font-body-sm text-on-surface text-center">Belum ada data orang tua.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-md">
                {{ $orangtua->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
