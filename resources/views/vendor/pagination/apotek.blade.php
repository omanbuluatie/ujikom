@if ($paginator->hasPages())
    <nav class="mt-6 space-y-3 text-sm" aria-label="Navigasi halaman">
        {{-- Baris 1: sebelumnya · info · berikutnya --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                @if ($paginator->onFirstPage())
                    <span class="btn btn-senyap cursor-not-allowed opacity-50 !py-2 !px-3">Halaman sebelumnya</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-senyap !py-2 !px-3" rel="prev">Halaman sebelumnya</a>
                @endif
            </div>

            <p class="text-[#5a6b76]">
                Halaman <span class="font-semibold text-[#1a2832]">{{ $paginator->currentPage() }}</span>
                dari <span class="font-semibold text-[#1a2832]">{{ $paginator->lastPage() }}</span>
            </p>

            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-senyap !py-2 !px-3" rel="next">Halaman berikutnya</a>
                @else
                    <span class="btn btn-senyap cursor-not-allowed opacity-50 !py-2 !px-3">Halaman berikutnya</span>
                @endif
            </div>
        </div>

        {{-- Baris 2: nomor halaman (klik) --}}
        <div class="flex flex-wrap items-center justify-center gap-1">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-[#8a9aa6]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded border border-[#0A5C44] bg-[#FFD54A]/40 px-2 py-1.5 font-semibold text-[#1a2832]" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex min-w-[2.25rem] items-center justify-center rounded border border-[#c5d0d8] bg-white px-2 py-1.5 text-[#1a2832] hover:border-[#0A5C44] hover:bg-[#FFD54A]/20">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Baris 3: lompat ke halaman tertentu (pertahankan filter GET) --}}
        <form method="GET" action="{{ $paginator->path() }}"
              class="flex flex-wrap items-end justify-center gap-2 border-t border-[#e6ecf0] pt-3">
            @foreach (request()->except('page') as $key => $value)
                @if (is_array($value))
                    @foreach ($value as $item)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div>
                <label for="lompat-halaman-{{ $paginator->getPageName() }}" class="label-lapangan !mb-1 text-xs">Lompat ke halaman</label>
                <input id="lompat-halaman-{{ $paginator->getPageName() }}"
                       type="number"
                       name="page"
                       min="1"
                       max="{{ $paginator->lastPage() }}"
                       value="{{ $paginator->currentPage() }}"
                       required
                       class="input-lapangan w-20 !py-1.5 text-center">
            </div>
            <button type="submit" class="btn btn-tiket !py-2 !px-4">Pergi</button>
        </form>
    </nav>
@endif
