<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — AbsenQu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-[#F1F8E9] min-h-screen flex items-center justify-center p-md">

<!-- Centered Login Card -->
<main class="w-full max-w-md bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col gap-xl shadow-none">
    <!-- Header / Logo Area -->
    <header class="flex flex-col items-center gap-xs text-center">
        <span class="material-symbols-outlined text-primary" style="font-size:48px">school</span>
        <h1 class="font-headline-lg text-headline-lg text-primary">AbsenQu</h1>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Sistem Kehadiran Digital · SMP 1 Palopo</p>
    </header>

    <!-- Session status -->
    @if (session('status'))
        <div class="text-sm text-secondary bg-secondary-container/30 border border-secondary-container px-sm py-xs rounded-lg text-center">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation errors -->
    @if ($errors->any())
        <div class="text-sm text-error bg-error-container/60 border border-error/20 px-sm py-xs rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" class="flex flex-col gap-lg" method="POST">
        @csrf
        <!-- Input Fields -->
        <div class="flex flex-col gap-md">
            <div class="flex flex-col gap-base">
                <label class="font-label-bold text-label-bold text-on-surface" for="email">Email</label>
                <input class="w-full h-12 px-sm border border-outline-variant rounded font-body-sm text-body-sm text-on-surface bg-surface-container-lowest focus:outline-none focus:border-primary-container focus:ring-0 transition-colors @error('email') border-error @enderror"
                    id="email" name="email" placeholder="nama@sekolah.edu" required type="email" value="{{ old('email') }}" autofocus autocomplete="email">
            </div>
            <div class="flex flex-col gap-base">
                <div class="flex justify-between items-center">
                    <label class="font-label-bold text-label-bold text-on-surface" for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a class="font-label-bold text-label-bold text-primary-container hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>
                <div class="relative">
                    <input class="w-full h-12 px-sm border border-outline-variant rounded font-body-sm text-body-sm text-on-surface bg-surface-container-lowest focus:outline-none focus:border-primary-container focus:ring-0 transition-colors @error('password') border-error @enderror"
                        id="password" name="password" placeholder="••••••••" required type="password" autocomplete="current-password">
                    <button class="absolute right-sm top-1/2 -translate-y-1/2 text-outline flex items-center justify-center" type="button" id="togglePwd">
                        <span class="material-symbols-outlined" style="font-size:20px" id="toggleIcon">visibility</span>
                    </button>
                </div>
            </div>
            <!-- Remember me -->
            <label class="flex items-center gap-xs cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-outline-variant text-primary-container focus:ring-primary-container">
                <span class="font-body-sm text-body-sm text-on-surface-variant">Ingat saya</span>
            </label>
        </div>
        <!-- Submit Button -->
        <button class="w-full h-12 bg-primary-container text-on-primary font-button-text text-button-text rounded flex items-center justify-center gap-xs hover:bg-primary active:border-2 active:border-primary-container transition-all" type="submit">
            Masuk ke Sistem
            <span class="material-symbols-outlined" style="font-size:18px">login</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="text-center">
        <p class="font-body-sm text-body-sm text-on-surface-variant">
            Akses diberikan oleh <span class="font-button-text text-button-text text-primary-container">Administrator Sekolah</span>
        </p>
    </div>
</main>

<script>
    const pwd = document.getElementById('password');
    const btn = document.getElementById('togglePwd');
    const icon = document.getElementById('toggleIcon');
    btn.addEventListener('click', () => {
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            pwd.type = 'password';
            icon.textContent = 'visibility';
        }
    });
</script>
</body>
</html>
