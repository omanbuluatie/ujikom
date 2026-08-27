@extends('layouts.tamu')
@section('judul', 'Masuk loket')
@section('isi')
<form method="POST" action="{{ route('masuk') }}" class="space-y-4">
    @csrf
    <div>
        <label class="label-lapangan" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="input-lapangan">
    </div>
    <div>
        <label class="label-lapangan" for="password">Kata sandi</label>
        <input id="password" name="password" type="password" required autocomplete="current-password" class="input-lapangan">
    </div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ingat"> Ingat saya di perangkat ini</label>
    <button class="btn btn-utama w-full">Masuk ke loket</button>
    <p class="text-center text-sm">Belum punya akun? <a class="font-semibold text-[#0A5C44]" href="{{ route('daftar') }}">Daftar sebagai pasien</a></p>
    <div class="border-t border-[#e3eaee] pt-3 font-mono text-[11px] leading-relaxed text-[#3D4C58]">
        Demo · kata sandi <strong>Password1</strong><br>
        admin@makmurjaya.test · apoteker@ · kasir@ · pasien@
    </div>
</form>
@endsection
