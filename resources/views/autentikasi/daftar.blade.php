@extends('layouts.tamu')
@section('judul', 'Daftar pasien')
@section('isi')
<form method="POST" action="{{ route('daftar') }}" class="space-y-3">
    @csrf
    <div>
        <label class="label-lapangan">Nama</label>
        <input name="name" value="{{ old('name') }}" required class="input-lapangan">
    </div>
    <div>
        <label class="label-lapangan">Email</label>
        <input name="email" type="email" value="{{ old('email') }}" required class="input-lapangan">
    </div>
    <div>
        <label class="label-lapangan">Telepon</label>
        <input name="telepon" value="{{ old('telepon') }}" class="input-lapangan">
    </div>
    <div>
        <label class="label-lapangan">Alamat</label>
        <input name="alamat" value="{{ old('alamat') }}" class="input-lapangan">
    </div>
    <div>
        <label class="label-lapangan">Kata sandi</label>
        <input name="password" type="password" required class="input-lapangan">
        <p class="mt-1 text-xs text-[#3D4C58]">Minimal 8 karakter, ada huruf dan angka.</p>
    </div>
    <div>
        <label class="label-lapangan">Ulangi kata sandi</label>
        <input name="password_confirmation" type="password" required class="input-lapangan">
    </div>
    <button class="btn btn-utama w-full">Buat akun pasien</button>
    <p class="text-center text-sm"><a href="{{ route('masuk') }}" class="underline">Sudah punya akun</a></p>
</form>
@endsection
