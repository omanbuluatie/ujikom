<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul') — Klinik Makmur Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:700|figtree:400,500,600|ibm-plex-mono:400" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-4 py-12">
        <a href="{{ route('katalog') }}" class="mb-6 text-center">
            <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-[#0A5C44]">Klinik Makmur Jaya</p>
            <h1 class="font-display text-3xl">Apotek Digital</h1>
        </a>
        <div class="kartu overflow-hidden">
            <div class="strip-fifo">TIKET LOKET · sesi otomatis 30 menit</div>
            <div class="p-6">
                <h2 class="font-display mb-4 text-xl">@yield('judul')</h2>
                @include('komponen.flash')
                @yield('isi')
            </div>
        </div>
    </div>
</body>
</html>
