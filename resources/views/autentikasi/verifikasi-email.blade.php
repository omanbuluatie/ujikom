@extends('layouts.tamu')
@section('judul', 'Verifikasi email')
@section('isi')
<p class="mb-4 text-sm leading-relaxed">Tautan verifikasi dikirim ke email akun ini. Untuk demo: minta admin buka <strong>Admin → Pemantauan</strong> → salin/buka tautan aktivasi — <strong>harus login sebagai akun pelanggan ini</strong> (logout admin dulu). Alternatif: admin verifikasi manual di menu Pelanggan.</p>
<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn btn-senyap w-full">Kirim ulang tautan</button>
</form>
@endsection
