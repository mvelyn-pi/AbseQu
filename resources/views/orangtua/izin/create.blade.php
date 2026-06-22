@extends('layouts.orangtua')
@section('title', 'Form Izin Digital — AbsenQu')
@section('back_route', route('orangtua.dashboard'))

@section('content')
<div class="max-w-3xl mx-auto p-container-margin md:p-xl flex flex-col gap-xl">
    {{-- Header --}}
    <div>
        <h2 class="text-headline-lg text-on-surface font-bold mb-base">Form Izin Digital</h2>
        <p class="text-body-sm text-on-surface-variant">Ajukan ketidakhadiran siswa dengan melampirkan bukti yang valid.</p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="p-sm rounded-lg bg-secondary-container border border-secondary flex items-center gap-sm">
        <span class="material-symbols-outlined text-secondary">check_circle</span>
        <span class="text-body-sm text-on-secondary-container font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-sm rounded-lg bg-error-container border border-error flex flex-col gap-xs">
        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-error">error</span>
            <span class="text-body-sm text-on-error-container font-semibold">Terdapat kesalahan pada pengisian form:</span>
        </div>
        <ul class="list-disc list-inside text-body-sm text-on-error-container ml-lg">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
        {{-- Green accent strip --}}
        <div class="h-2 w-full bg-primary-container opacity-20"></div>

        <form action="{{ route('orangtua.izin.store') }}" method="POST" enctype="multipart/form-data" class="p-container-margin md:p-xl flex flex-col gap-lg">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                {{-- Date --}}
                <div class="flex flex-col gap-base">
                    <label class="text-label-bold text-on-surface-variant uppercase tracking-wider" for="tanggal_izin">TANGGAL MULAI IZIN</label>
                    <input type="date" name="tanggal_izin" id="tanggal_izin"
                        value="{{ old('tanggal_izin') }}"
                        min="{{ today()->format('Y-m-d') }}"
                        class="px-md py-sm border border-outline-variant rounded-lg bg-surface text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                {{-- Type --}}
                <div class="flex flex-col gap-base">
                    <label class="text-label-bold text-on-surface-variant uppercase tracking-wider" for="jenis">JENIS KETIDAKHADIRAN</label>
                    <select name="jenis" id="jenis" class="px-md py-sm border border-outline-variant rounded-lg bg-surface text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary appearance-none">
                        <option value="" disabled selected>Pilih jenis izin...</option>
                        <option value="Sakit" {{ old('jenis') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ old('jenis') === 'Izin' ? 'selected' : '' }}>Izin (Keperluan Keluarga/Lainnya)</option>
                    </select>
                </div>
            </div>

            {{-- Reason --}}
            <div class="flex flex-col gap-base">
                <label class="text-label-bold text-on-surface-variant uppercase tracking-wider" for="alasan">ALASAN DETAIL</label>
                <textarea name="alasan" id="alasan" rows="4" placeholder="Jelaskan alasan ketidakhadiran secara singkat..."
                    class="px-md py-sm border border-outline-variant rounded-lg bg-surface text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary resize-y">{{ old('alasan') }}</textarea>
            </div>

            {{-- File Upload --}}
            <div class="flex flex-col gap-base">
                <label class="text-label-bold text-on-surface-variant uppercase tracking-wider">UNGGAH BUKTI (SURAT DOKTER/SURAT WALI)</label>
                <div class="border border-outline-variant border-dashed bg-surface rounded-lg p-lg flex flex-col items-center justify-center gap-sm text-center hover:bg-surface-container-low transition-colors cursor-pointer"
                    onclick="document.getElementById('bukti').click()">
                    <span class="material-symbols-outlined text-outline text-3xl">upload_file</span>
                    <div class="text-body-sm text-on-surface-variant">
                        <p>Tarik &amp; lepas file di sini atau <span class="text-primary font-semibold">klik untuk mencari</span></p>
                        <p class="text-xs mt-1 opacity-70">Maks. ukuran file 5MB (JPG, PNG, PDF)</p>
                    </div>
                    <input type="file" name="bukti" id="bukti" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                    <span id="fileName" class="text-xs text-on-surface-variant hidden"></span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse md:flex-row justify-end gap-md mt-sm pt-lg border-t border-outline-variant">
                <a href="{{ route('orangtua.dashboard') }}" class="px-md py-sm border border-outline-variant text-on-surface-variant rounded-lg font-semibold hover:bg-surface-container-low transition-colors text-center text-sm">Batal</a>
                <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-semibold hover:bg-primary-container transition-colors flex items-center justify-center gap-xs text-sm">
                    <span class="material-symbols-outlined" style="font-size:18px">send</span>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>


    {{-- Previous requests --}}
    <div class="flex flex-col gap-md">
        <h3 class="text-headline-md text-on-surface font-semibold">Status Pengajuan Sebelumnya</h3>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden">
            <div class="grid grid-cols-4 gap-md px-lg py-sm bg-surface-container-low border-b border-outline-variant text-label-bold text-on-surface-variant uppercase tracking-wider">
                <div>TANGGAL</div>
                <div>JENIS</div>
                <div>DOKUMEN</div>
                <div class="text-right">STATUS</div>
            </div>
            @forelse($previousRequests ?? [] as $req)
            <div class="grid grid-cols-4 gap-md px-lg py-md border-b border-outline-variant items-center hover:bg-surface transition-colors">
                <div class="text-body-sm text-on-surface">{{ $req->tanggal_izin ? $req->tanggal_izin->format('d M Y') : '-' }}</div>
                <div class="text-body-sm text-on-surface">
                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#006e1c] text-white uppercase">{{ $req->jenis }}</span>
                </div>
                <div class="flex items-center gap-xs">
                    @if($req->bukti)
                        <span class="material-symbols-outlined text-outline" style="font-size:18px">description</span>
                        <a href="{{ $req->bukti_url }}" target="_blank" class="text-body-sm text-primary hover:underline cursor-pointer text-xs">{{ basename($req->bukti) }}</a>
                    @else
                        <span class="text-body-sm text-outline italic text-xs">Tidak ada bukti</span>
                    @endif
                </div>
                <div class="flex justify-end">
                    @if($req->status === 'Pending')
                        <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#FBC02D] text-[#3E2723]">PENDING</span>
                    @elseif($req->status === 'Approved')
                        <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#4CAF50] text-white">DISETUJUI</span>
                    @elseif($req->status === 'Rejected')
                        <span class="inline-block px-2 py-1 text-xs font-bold rounded-sm bg-[#D32F2F] text-white">DITOLAK</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-lg py-lg text-center text-on-surface-variant text-body-sm">
                Belum ada pengajuan izin sebelumnya
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.getElementById('bukti').addEventListener('change', function() {
        const nameEl = document.getElementById('fileName');
        if (this.files.length > 0) {
            nameEl.textContent = this.files[0].name;
            nameEl.classList.remove('hidden');
        }
    });
</script>
@endsection
