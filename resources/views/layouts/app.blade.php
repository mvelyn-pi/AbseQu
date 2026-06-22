<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AbsenQu' }} — AbsenQu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{ $styles ?? '' }}
    <style>
        body { font-family: 'Inter', sans-serif; }
        * { box-shadow: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Mobile Sidebar Overrides */
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        .mobile-overlay-hidden { display: none; }
        @media (max-width: 767px) {
            .mobile-sidebar-hidden { transform: translateX(-100%); }
            .mobile-sidebar-visible { transform: translateX(0); }
            .mobile-overlay-visible { display: block; }
        }
        @media (min-width: 768px) {
            #mobile-overlay { display: none !important; }
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex antialiased">

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 mobile-overlay-hidden cursor-pointer transition-opacity"></div>

<!-- SideNavBar -->
<nav id="sidebar" class="mobile-sidebar-hidden sidebar-transition fixed md:translate-x-0 left-0 top-0 h-full flex-col w-64 border-r border-outline-variant bg-surface z-50 flex">
    <!-- Brand Header -->
    <div class="p-lg border-b border-outline-variant flex items-center gap-sm">
        <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-on-primary font-headline-md text-headline-md font-bold flex-shrink-0">
            A
        </div>
        <div>
            <h1 class="font-headline-md text-headline-md text-primary font-bold">AbsenQu</h1>
            <p class="font-label-bold text-label-bold text-on-surface-variant uppercase">{{ ucfirst(Auth::user()->role ?? 'portal') }} Portal</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-md flex flex-col gap-base px-sm font-body-sm text-body-sm">
        @if(Auth::user()->role === 'admin')
            <div class="px-sm mb-xs">
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase px-sm py-xs">Administrator</p>
            </div>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.dashboard') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? '' : 'transition-transform duration-150 group-hover:scale-95' }}" {{ request()->routeIs('admin.dashboard') ? 'style=\'font-variation-settings: "FILL" 1;\'' : '' }}>dashboard</span>
                <span class="font-body-sm text-body-sm {{ request()->routeIs('admin.dashboard') ? '' : 'font-medium' }}">Dashboard</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.siswa.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.siswa.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">person</span>
                <span class="font-body-sm text-body-sm font-medium">Data Siswa</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.kelas.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.kelas.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">groups</span>
                <span class="font-body-sm text-body-sm font-medium">Data Kelas</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.orangtua.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.orangtua.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">family_restroom</span>
                <span class="font-body-sm text-body-sm font-medium">Data Orang Tua</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.guru.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.guru.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">badge</span>
                <span class="font-body-sm text-body-sm font-medium">Data Guru</span>
            </a>

            <div class="px-sm mt-md mb-xs">
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase px-sm py-xs">Absensi</p>
            </div>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.scanner') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.scanner') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95" {{ request()->routeIs('guru.scanner') ? 'style=\'font-variation-settings: "FILL" 1;\'' : '' }}>qr_code_scanner</span>
                <span class="font-body-sm text-body-sm font-medium">Scanner QR</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.absensi.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.absensi.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">assessment</span>
                <span class="font-body-sm text-body-sm font-medium">Rekap Absensi</span>
            </a>

            <div class="px-sm mt-md mb-xs">
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase px-sm py-xs">Others</p>
            </div>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.izin.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.izin.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">article</span>
                <span class="font-body-sm text-body-sm font-medium">Pengajuan Izin</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.leaderboard') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.leaderboard') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">leaderboard</span>
                <span class="font-body-sm text-body-sm font-medium">Leaderboard</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.notifikasi.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.notifikasi.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">notifications</span>
                <span class="font-body-sm text-body-sm font-medium">Notifikasi WA</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('admin.settings.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('admin.settings.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">settings</span>
                <span class="font-body-sm text-body-sm font-medium">Pengaturan</span>
            </a>

        @elseif(Auth::user()->role === 'guru')
            <div class="px-sm mb-xs">
                <p class="font-label-bold text-label-bold text-on-surface-variant uppercase px-sm py-xs">Guru</p>
            </div>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.dashboard') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.dashboard') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">dashboard</span>
                <span class="font-body-sm text-body-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.scanner') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.scanner') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95" {{ request()->routeIs('guru.scanner') ? 'style=\'font-variation-settings: "FILL" 1;\'' : '' }}>qr_code_scanner</span>
                <span class="font-body-sm text-body-sm font-medium">Scanner QR</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.absensi.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.absensi.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">assessment</span>
                <span class="font-body-sm text-body-sm font-medium">Rekap Absensi</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.izin.*') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.izin.index') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">article</span>
                <span class="font-body-sm text-body-sm font-medium">Pengajuan Izin</span>
            </a>
            <a class="flex items-center gap-sm px-sm py-sm {{ request()->routeIs('guru.leaderboard') ? 'text-secondary font-bold border-r-2 border-secondary bg-surface-container-low rounded-lg' : 'rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group' }}" href="{{ route('guru.leaderboard') }}">
                <span class="material-symbols-outlined transition-transform duration-150 group-hover:scale-95">leaderboard</span>
                <span class="font-body-sm text-body-sm font-medium">Leaderboard</span>
            </a>
        @endif
    </div>

    <!-- User Profile Footer -->
    <div class="p-md border-t border-outline-variant flex items-center gap-sm cursor-pointer hover:bg-surface-container-low transition-colors">
        <div class="w-8 h-8 rounded-full bg-surface-variant border border-outline flex items-center justify-center overflow-hidden flex-shrink-0">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">account_circle</span>
        </div>
        <div class="flex-1 overflow-hidden">
            <p class="font-body-sm text-body-sm font-semibold truncate text-on-surface">{{ Auth::user()->name }}</p>
            <p class="font-label-bold text-label-bold text-on-surface-variant truncate uppercase">{{ Auth::user()->role }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-on-surface-variant hover:text-error transition-colors" title="Logout">
                <span class="material-symbols-outlined" style="font-size:18px">logout</span>
            </button>
        </form>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col md:ml-64 w-full">
    <!-- TopNavBar -->
    <header class="flex justify-between items-center w-full px-lg py-sm sticky top-0 z-40 bg-surface border-b border-outline-variant">
        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-btn" class="md:hidden text-primary p-xs rounded hover:bg-surface-container-low">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="flex-1 max-w-md mx-md hidden md:block">
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-sm text-on-surface-variant" style="font-size:20px">search</span>
                <input class="w-full pl-xl pr-sm py-xs border border-outline-variant rounded-full font-body-sm text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-surface" placeholder="Search..." type="text">
            </div>
        </div>
        <div class="flex items-center gap-md">
            <button class="text-on-surface-variant hover:text-primary transition-opacity opacity-80 hover:opacity-100">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="text-on-surface-variant hover:text-primary transition-opacity opacity-80 hover:opacity-100 flex items-center gap-xs">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 p-md md:p-container-margin overflow-y-auto bg-background">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>
</div>

@livewireScripts
{{ $scripts ?? '' }}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('mobile-sidebar-hidden');
            sidebar.classList.toggle('mobile-sidebar-visible');
            
            mobileOverlay.classList.toggle('mobile-overlay-hidden');
            mobileOverlay.classList.toggle('mobile-overlay-visible');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleSidebar);
        }
    });
</script>
</body>
</html>
