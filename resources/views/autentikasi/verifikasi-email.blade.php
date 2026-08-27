@extends('layouts.tamu')
@section('judul', 'Verifikasi email')
@section('isi')
<p class="mb-4 text-sm leading-relaxed">Tautan verifikasi dikirim ke email akun ini. Untuk demo: minta admin buka <strong>Admin → Pemantauan</strong> → bagian <strong>Tautan aktivasi email</strong>, lalu klik <strong>Buka tautan</strong>. (Alternatif: <span class="font-mono text-xs">storage/logs/laravel.log</span> jika mail driver log.)</p>
<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn btn-senyap w-full">Kirim ulang tautan</button>
</form>
@endsection
