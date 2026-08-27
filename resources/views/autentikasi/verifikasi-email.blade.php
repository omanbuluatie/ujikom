@extends('layouts.tamu')
@section('judul', 'Verifikasi email')
@section('isi')
<p class="mb-4 text-sm leading-relaxed">Tautan verifikasi dikirim ke email akun ini. Untuk demo uji kompetensi, buka <span class="font-mono text-xs">storage/logs/laravel.log</span> — mail memakai driver log.</p>
<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn btn-senyap w-full">Kirim ulang tautan</button>
</form>
@endsection
