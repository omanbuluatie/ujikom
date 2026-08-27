<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul') — Meja staf Makmur Jaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700|figtree:400,500,600|ibm-plex-mono:400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen" x-data="{ badge: 0 }" x-init="
    @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Admin))
        const muat = () => fetch('{{ route('admin.dasbor.polling') }}').then(r => r.json()).then(d => badge = d.belum_dibaca);
        muat(); setInterval(muat, 10000);
    @endif
">
    <div class="flex min-h-screen">
        <aside class="hidden w-[15.5rem] shrink-0 flex-col bg-[#17202A] text-white md:flex">
            <div class="px-5 pt-6 pb-5">
                <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-[#FFD54A]">Meja staf</p>
                <p class="font-display mt-1 text-xl leading-tight">Makmur Jaya</p>
            </div>
            <nav class="nav-laci flex-1 pb-8">
                @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Kasir, \App\Enums\PeranPengguna::Admin))
                    <p class="px-4 pb-1 pt-3 font-mono text-[10px] uppercase tracking-widest text-[#FFD54A]/70">Loket</p>
                    <a href="{{ route('kasir.transaksi') }}" class="{{ request()->routeIs('kasir.*') ? 'aktif' : '' }}">Kasir counter</a>
                @endif
                @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Apoteker, \App\Enums\PeranPengguna::Admin))
                    <p class="px-4 pb-1 pt-3 font-mono text-[10px] uppercase tracking-widest text-[#FFD54A]/70">Apotek</p>
                    <a href="{{ route('apoteker.resep.index') }}" class="{{ request()->routeIs('apoteker.resep.*') ? 'aktif' : '' }}">Antrian resep</a>
                    <a href="{{ route('apoteker.stok') }}" class="{{ request()->routeIs('apoteker.stok') ? 'aktif' : '' }}">Laci stok FIFO</a>
                @endif
                @if(auth()->user()->adalah(\App\Enums\PeranPengguna::Admin))
                    <p class="px-4 pb-1 pt-3 font-mono text-[10px] uppercase tracking-widest text-[#FFD54A]/70">Kendali</p>
                    <a href="{{ route('admin.dasbor') }}" class="{{ request()->routeIs('admin.dasbor*') ? 'aktif' : '' }}">
                        Papan antrian
                        <span class="tiket tiket--tunggu" x-show="badge > 0" x-text="badge" x-cloak></span>
                    </a>
                    <a href="{{ route('admin.obat.index') }}" class="{{ request()->routeIs('admin.obat.*') ? 'aktif' : '' }}">Obat</a>
                    <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'aktif' : '' }}">Kategori</a>
                    <a href="{{ route('admin.pemasok.index') }}" class="{{ request()->routeIs('admin.pemasok.*') ? 'aktif' : '' }}">Pemasok</a>
                    <a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan.*') ? 'aktif' : '' }}">Pelanggan</a>
                    <a href="{{ route('admin.transaksi.index') }}" class="{{ request()->routeIs('admin.transaksi.*') ? 'aktif' : '' }}">Transaksi</a>
                    <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan*') ? 'aktif' : '' }}">Laporan</a>
                    <a href="{{ route('admin.migrasi') }}" class="{{ request()->routeIs('admin.migrasi*') ? 'aktif' : '' }}">Migrasi CSV</a>
                    <a href="{{ route('admin.pemantauan') }}" class="{{ request()->routeIs('admin.pemantauan*') ? 'aktif' : '' }}">Pemantauan</a>
                @endif
                <p class="px-4 pb-1 pt-3 font-mono text-[10px] uppercase tracking-widest text-[#FFD54A]/70">Publik</p>
                <a href="{{ route('katalog') }}">Lihat katalog</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-[#d5dee4] bg-white px-4 py-3 md:px-8">
                <div>
                    <h1 class="font-display text-xl">@yield('judul')</h1>
                    <p class="text-xs text-[#3D4C58]">@yield('subjudul')</p>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <span class="tiket tiket--proses">{{ auth()->user()->peran->label() }}</span>
                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('keluar') }}">@csrf
                        <button class="text-[#C23B22]">Keluar</button>
                    </form>
                </div>
            </header>
            <main class="flex-1 p-4 md:p-8">
                @include('komponen.flash')
                @yield('isi')
            </main>
        </div>
    </div>
</body>
</html>
