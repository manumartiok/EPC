@php
    $calidad      = App\Models\Calidad::first() ?? new App\Models\Calidad();
    $certificados = App\Models\Certificado::where('active', true)->orderBy('orden')->get();
@endphp

@extends('layouts.web')

@section('title', 'Calidad')

@section('content')

<style>
    /* Descripción (HTML del editor): las listas con viñetas llevan el ícono de check del diseño */
    .calidad-desc p { margin: 0; }
    .calidad-desc p + p, .calidad-desc p + ul, .calidad-desc ul + p { margin-top: 24px; }
    .calidad-desc ul { list-style: none; margin: 16px 0 0; padding: 0; }
    .calidad-desc li { position: relative; padding-left: 28px; line-height: 2.1; }
    .calidad-desc li::before {
        content: ''; position: absolute; left: 0; top: 50%; width: 20px; height: 20px; transform: translateY(-50%);
        background: url('{{ asset('assets/img/calidad/circle-check.svg') }}') center/contain no-repeat;
    }
</style>

<div class="letra bg-[#F2F5F8] text-[#303030]">
    <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[120px]">

        {{-- breadcrumb --}}
        <p class="text-[12px] leading-[1.5]"><a href="{{ route('home') }}" class="font-bold">Inicio</a> &gt; Calidad</p>

        {{-- encabezado --}}
        <div class="mt-[72px]">
            @if($calidad->subtitulo)
                <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $calidad->subtitulo }}</p>
            @endif
            @if($calidad->titulo)
                <h1 class="mt-[1px] max-w-[736px] text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">{{ $calidad->titulo }}</h1>
            @endif
            @if($calidad->texto)
                <p class="mt-[8px] max-w-[600px] text-[16px] leading-[1.5]">{{ $calidad->texto }}</p>
            @endif
        </div>

        {{-- foto + descripción --}}
        @if($calidad->foto || $calidad->descripcion)
        <div class="mt-[40px] grid grid-cols-1 lg:grid-cols-2 gap-[24px]">
            <div>
                @if($calidad->foto)
                    <img src="{{ asset($calidad->foto) }}" alt="{{ $calidad->titulo }}" class="w-[600px] max-w-full h-[300px] object-cover rounded-[2px]">
                @endif
            </div>

            @if($calidad->descripcion)
                <div class="calidad-desc text-[16px] leading-[1.5] text-black">{!! $calidad->descripcion !!}</div>
            @endif
        </div>
        @endif

        {{-- certificados --}}
        @if($certificados->isNotEmpty())
        <div class="mt-[40px] grid grid-cols-1 lg:grid-cols-2 gap-x-[24px] gap-y-[16px]">
            @foreach($certificados as $certificado)
                <div class="flex items-center gap-[10px] border-y border-[#DEDFE0] py-[4px]">
                    {{-- foto --}}
                    <div class="shrink-0 size-[60px] bg-[#F9F9F9] border border-[#DEDFE0] rounded-[1px] flex items-center justify-center overflow-hidden">
                        @if($certificado->foto)
                            <img src="{{ asset($certificado->foto) }}" alt="{{ $certificado->nombre }}" class="w-full h-full object-cover">
                        @endif
                    </div>

                    {{-- nombre + formato y peso --}}
                    <div class="min-w-0 flex-1">
                        <p class="text-[20px] leading-[1.2] font-bold text-black">{{ $certificado->nombre }}</p>
                        @if($certificado->archivo)
                            <p class="mt-[2px] text-[14px] leading-[1.5] text-black">
                                {{ strtoupper($certificado->extension()) }}@if($certificado->pesoFormateado()) - {{ $certificado->pesoFormateado() }}@endif
                            </p>
                        @endif
                    </div>

                    {{-- imagen (se amplía) o descarga --}}
                    @if($certificado->archivo)
                        @if($certificado->archivoEsImagen())
                            <button type="button" class="certificado-ampliar shrink-0 w-[57px] h-[58px]"
                                    data-src="{{ asset($certificado->archivo) }}" data-alt="{{ $certificado->nombre }}"
                                    aria-label="Ampliar {{ $certificado->nombre }}">
                                <img src="{{ asset($certificado->archivo) }}" alt="{{ $certificado->nombre }}" class="w-full h-full object-cover">
                            </button>
                        @else
                            <a href="{{ asset($certificado->archivo) }}" download target="_blank" rel="noopener"
                               class="shrink-0 flex items-center justify-center w-[57px] h-[58px]" aria-label="Descargar {{ $certificado->nombre }}">
                                <img src="{{ asset('assets/img/catalogo/download.svg') }}" alt="" class="w-[20px] h-[20px]">
                            </a>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- imagen ampliada --}}
<div id="certificado-visor" class="hidden fixed inset-0 z-[100] bg-black/80 items-center justify-center p-[5%] cursor-zoom-out">
    <img id="certificado-visor-img" src="" alt="" class="max-w-full max-h-full object-contain bg-white">
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const visor = document.getElementById('certificado-visor');
    const visorImg = document.getElementById('certificado-visor-img');

    function cerrar() {
        visor.classList.add('hidden');
        visor.classList.remove('flex');
        visorImg.setAttribute('src', '');
    }

    document.querySelectorAll('.certificado-ampliar').forEach(function (boton) {
        boton.addEventListener('click', function () {
            visorImg.setAttribute('src', this.dataset.src);
            visorImg.setAttribute('alt', this.dataset.alt || '');
            visor.classList.remove('hidden');
            visor.classList.add('flex');
        });
    });

    visor.addEventListener('click', cerrar);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') cerrar();
    });
});
</script>
@endsection
