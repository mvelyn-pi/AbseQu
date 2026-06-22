@extends('layouts.app')
@section('title', 'QR Code Siswa')
@section('header', 'QR Code Siswa')

@section('content')
<div class="max-w-md mx-auto space-y-6">
    <div class="card text-center">
        <img src="{{ $siswa->foto_url }}" class="w-20 h-20 rounded-2xl mx-auto mb-4 object-cover" alt="">
        <h2 class="text-xl font-bold text-white">{{ $siswa->nama }}</h2>
        <p class="text-gray-400 text-sm">NIS: {{ $siswa->nis }} • {{ $siswa->kelas->nama_kelas ?? '-' }}</p>

        <div class="my-6 p-4 bg-white rounded-2xl inline-block mx-auto">
            {!! $qrSvg !!}
        </div>

        <code class="block text-xs text-indigo-300 bg-indigo-900/30 px-4 py-2 rounded-xl mb-6">
            {{ $siswa->qr_code }}
        </code>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('admin.siswa.qr.download', $siswa) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download SVG
            </a>
            <a href="{{ route('admin.siswa.idcard', $siswa) }}" target="_blank" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak ID Card
            </a>
        </div>
    </div>
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary w-full justify-center">← Kembali ke Daftar Siswa</a>
</div>
@endsection
