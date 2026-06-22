<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Orang Tua — AbsenQu')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F1F8E9] text-on-background font-inter antialiased min-h-screen flex flex-col">

{{-- TopNavBar for Orang Tua --}}
<nav class="bg-surface border-b border-outline-variant flex justify-between items-center w-full px-lg py-sm sticky top-0 z-40 shadow-sm">
    <div class="flex items-center gap-sm">
        @hasSection('back_route')
        <a href="@yield('back_route')" class="flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined" style="font-size:20px">arrow_back</span>
        </a>
        @endif
        <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1">school</span>
        <a href="{{ route('orangtua.dashboard') }}" class="flex items-center">
            <span class="text-headline-md text-primary font-bold hidden sm:inline">AbsenQu</span>
            <span class="text-label-bold text-on-surface-variant ml-xs hidden sm:inline">Portal Orang Tua</span>
        </a>
    </div>
    
    <div class="flex items-center gap-md">
        <a href="{{ route('orangtua.dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors font-semibold text-sm hidden md:block">Beranda</a>
        <a href="{{ route('orangtua.absensi') }}" class="text-on-surface-variant hover:text-primary transition-colors font-semibold text-sm hidden md:block">Absensi</a>
        <a href="{{ route('orangtua.izin.index') }}" class="text-on-surface-variant hover:text-primary transition-colors font-semibold text-sm hidden md:block">Izin</a>
        
        <div class="w-px h-6 bg-outline-variant mx-xs hidden md:block"></div>
        
        <button class="text-on-surface-variant hover:text-primary transition-colors opacity-80 hover:opacity-100 hidden sm:block">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="flex items-center gap-xs">
            <div class="w-8 h-8 rounded-full bg-surface-variant border border-outline-variant flex items-center justify-center overflow-hidden cursor-pointer">
                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">account_circle</span>
            </div>
            <span class="text-body-sm text-on-surface-variant hidden md:block font-medium">{{ Auth::user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-on-surface-variant hover:text-error transition-colors text-sm flex items-center gap-xs ml-xs" title="Logout">
                <span class="material-symbols-outlined" style="font-size:20px">logout</span>
            </button>
        </form>
    </div>
</nav>

{{-- Mobile Bottom Nav (Optional, for better UX on small screens) --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-surface border-t border-outline-variant z-50 flex justify-around items-center py-2 pb-safe">
    <a href="{{ route('orangtua.dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('orangtua.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('orangtua.dashboard') ? 'style=font-variation-settings:\'FILL\'_1' : '' }}>home</span>
        <span class="text-[10px] font-semibold mt-1">Beranda</span>
    </a>
    <a href="{{ route('orangtua.absensi') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('orangtua.absensi') ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('orangtua.absensi') ? 'style=font-variation-settings:\'FILL\'_1' : '' }}>calendar_today</span>
        <span class="text-[10px] font-semibold mt-1">Absensi</span>
    </a>
    <a href="{{ route('orangtua.izin.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('orangtua.izin.*') ? 'text-primary' : 'text-on-surface-variant' }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('orangtua.izin.*') ? 'style=font-variation-settings:\'FILL\'_1' : '' }}>edit_document</span>
        <span class="text-[10px] font-semibold mt-1">Izin</span>
    </a>
</div>

{{-- Main Content --}}
<main class="flex-1 w-full pb-20 md:pb-0">
    @yield('content')
</main>

@livewireScripts
</body>
</html>
