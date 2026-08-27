@php
    $jumlahKeranjang = app(\App\Layanan\LayananKeranjang::class)->jumlahItem();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Katalog') — Apotek Klinik Makmur Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700|figtree:400,500,600|ibm-plex-mono:400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <header class="border-b border-[#d5dee4] bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 md:px-6">
            <a href="{{ route('katalog') }}" class="flex items-center gap-3">
                <span class="strip-fifo !px-2 !py-1 text-[10px]">LOKET</span>
                <span>
                    <span class="block font-mono text-[10px] uppercase tracking-[0.22em] text-[#0A5C44]">Klinik Makmur Jaya</span>
                    <span class="font-display text-lg leading-none">Apotek</span>
                </span>
            </a>
            <nav class="hidden items-center gap-5 text-sm md:flex">
                <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog*') ? 'font-semibold' : 'text-[#3D4C58]' }}">Katalog</a>
                @auth
                    <a href="{{ route('keranjang') }}" class="{{ request()->routeIs('keranjang*') ? 'font-semibold' : 'text-[#3D4C58]' }}">
                        Keranjang @if($jumlahKeranjang > 0)<span class="tiket tiket--tunggu">{{ $jumlahKeranjang }}</span>@endif
                    </a>
                    <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan*') ? 'font-semibold' : 'text-[#3D4C58]' }}">Pesanan</a>
                @endauth
            </nav>
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <span class="hidden font-mono text-[10px] uppercase text-[#0A5C44] sm:inline">{{ auth()->user()->peran->label() }}</span>
                    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Admin))
                        <a href="{{ route('admin.dasbor') }}" class="text-[#3D4C58] hover:underline">Meja staf</a>
                    @elseif(auth()->user()->adalah(\App\Enums\PeranPengguna::Kasir))
                        <a href="{{ route('kasir.transaksi') }}" class="text-[#3D4C58] hover:underline">Meja kasir</a>
                    @elseif(auth()->user()->adalah(\App\Enums\PeranPengguna::Apoteker))
                        <a href="{{ route('apoteker.resep.index') }}" class="text-[#3D4C58] hover:underline">Meja apoteker</a>
                    @endif
                    <form method="POST" action="{{ route('keluar') }}">@csrf
                        <button class="text-[#C23B22]">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('masuk') }}" class="btn btn-utama !py-2">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 pb-20 md:px-6 md:pb-8">
        @include('komponen.flash')
        @yield('isi')
    </main>

    <nav class="fixed inset-x-0 bottom-0 flex border-t border-[#d5dee4] bg-white text-xs md:hidden">
        <a class="flex-1 py-3 text-center" href="{{ route('katalog') }}">Katalog</a>
        @auth
            <a class="flex-1 py-3 text-center" href="{{ route('keranjang') }}">Keranjang</a>
            <a class="flex-1 py-3 text-center" href="{{ route('pesanan.index') }}">Pesanan</a>
        @else
            <a class="flex-1 py-3 text-center" href="{{ route('masuk') }}">Masuk</a>
        @endauth
    </nav>
</body>
</html>
