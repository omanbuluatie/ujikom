@props(['judul', 'teks' => '', 'aksi' => null, 'tautan' => null])
<div class="kartu px-6 py-12 text-center">
    <p class="font-display text-xl">{{ $judul }}</p>
    <p class="mt-2 text-sm text-[#3D4C58]">{{ $teks }}</p>
    @if($aksi && $tautan)
        <a href="{{ $tautan }}" class="btn btn-utama mt-5">{{ $aksi }}</a>
    @endif
</div>
