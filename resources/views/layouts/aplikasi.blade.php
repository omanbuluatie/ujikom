<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Apotek') — Klinik Makmur Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700|figtree:400,500,600|ibm-plex-mono:400,500" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 bg-[#1B2838] text-white md:flex md:flex-col">
            <div class="px-5 pt-6 pb-4">
                <p class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#F4C430]">Klinik Makmur Jaya</p>
                <h1 class="font-display mt-1 text-xl leading-tight">Apotek Digital</h1>
            </div>
            <nav class="laci-nav flex-1 space-y-0.5 px-2 pb-6 text-sm">
                <a href="{{ route('katalog') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10 {{ request()->routeIs('katalog*') ? 'aktif text-[#1B2838]' : '' }}">Katalog</a>
                @auth
                    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Pasien, \App\Enums\PeranPengguna::Admin))
                        <a href="{{ route('keranjang') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Keranjang</a>
                        <a href="{{ route('pesanan.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Pesanan saya</a>
                    @endif
                    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Kasir, \App\Enums\PeranPengguna::Admin))
                        <a href="{{ route('kasir.transaksi') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Kasir</a>
                    @endif
                    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Apoteker, \App\Enums\PeranPengguna::Admin))
                        <a href="{{ route('apoteker.resep.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Verifikasi resep</a>
                        <a href="{{ route('apoteker.stok') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Stok FIFO</a>
                    @endif
                    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Admin))
                        <p class="mt-4 px-3 font-mono text-[10px] uppercase tracking-widest text-[#F4C430]">Admin</p>
                        <a href="{{ route('admin.dasbor') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Dasbor</a>
                        <a href="{{ route('admin.obat.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Obat</a>
                        <a href="{{ route('admin.kategori.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Kategori</a>
                        <a href="{{ route('admin.pemasok.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Pemasok</a>
                        <a href="{{ route('admin.pelanggan.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Pelanggan</a>
                        <a href="{{ route('admin.transaksi.index') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Transaksi</a>
                        <a href="{{ route('admin.laporan') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Laporan</a>
                        <a href="{{ route('admin.migrasi') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Migrasi</a>
                        <a href="{{ route('admin.pemantauan') }}" class="block rounded-r px-3 py-2 text-white/80 hover:bg-white/10">Pemantauan</a>
                    @endif
                @endauth
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-[#d5dde3] bg-white px-4 py-3 md:px-8">
                <div>
                    <h2 class="font-display text-lg">@yield('judul')</h2>
                    <p class="text-xs text-[#4E5D68]">@yield('subjudul', 'Antrian laci obat · FIFO')</p>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    @auth
                        <span class="font-mono text-xs uppercase tracking-wide text-[#0B6B4F]">{{ auth()->user()->peran->label() }}</span>
                        <span>{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('keluar') }}">@csrf
                            <button class="text-[#C23B22] underline-offset-2 hover:underline">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('masuk') }}" class="font-medium text-[#0B6B4F]">Masuk</a>
                    @endauth
                </div>
            </header>

            <main class="flex-1 p-4 md:p-8">
                @if(session('status'))
                    <div class="mb-4 border-l-4 border-[#F4C430] bg-white px-4 py-3 text-sm">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 border-l-4 border-[#C23B22] bg-white px-4 py-3 text-sm text-[#C23B22]">
                        {{ $errors->first() }}
                    </div>
                @endif
                @yield('isi')
            </main>
        </div>
    </div>
</body>
</html>
