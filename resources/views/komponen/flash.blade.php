@if(session('status'))
    <div class="flash-ok mb-5 text-sm" role="status">{{ session('status') }}</div>
@endif
@if($errors->any())
    <div class="flash-err mb-5 text-sm" role="alert">
        <p class="font-semibold">Periksa isian berikut</p>
        <ul class="mt-1 list-disc pl-4">
            @foreach($errors->all() as $pesan)
                <li>{{ $pesan }}</li>
            @endforeach
        </ul>
    </div>
@endif
