@php
    $catalogo = App\Models\Catalogo::first() ?? new App\Models\Catalogo();

    $categorias = collect($catalogo->categorias ?? [])->filter(fn ($c) => !empty($c['nombre']))->values();

    $meses = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
    $actualizado = $catalogo->fecha_archivo
        ? $meses[$catalogo->fecha_archivo->month - 1] . ' ' . $catalogo->fecha_archivo->year
        : null;
    $peso = $catalogo->pesoFormateado();
@endphp

@extends('layouts.web')

@section('title', 'Catálogo')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">

<div class="letra bg-[#F2F5F8] text-[#303030]">
    <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[60px]">

        {{-- breadcrumb --}}
        <p class="text-[12px] leading-[1.5]"><a href="{{ route('home') }}" class="font-bold">Inicio</a> &gt; Catálogo</p>

        {{-- encabezado --}}
        <div class="mt-[70px]">
            @if($catalogo->subtitulo1)
                <p class="text-[16px] leading-[1.5] text-[#086AFF]">{{ $catalogo->subtitulo1 }}</p>
            @endif
            @if($catalogo->titulo1)
                <h1 class="mt-[1px] max-w-[564px] text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">{{ $catalogo->titulo1 }}</h1>
            @endif
            @if($catalogo->texto)
                <div class="mt-[24px] max-w-[600px] text-[16px] leading-[1.5]">{!! $catalogo->texto !!}</div>
            @endif
        </div>

        {{-- catálogo completo --}}
        @if($catalogo->archivo || $catalogo->titulo_archivo)
        <div class="mt-[44px] bg-[#003879] text-white px-[5%] lg:px-0 py-[40px] lg:py-0 lg:min-h-[450px] grid grid-cols-1 lg:grid-cols-2 items-center gap-[40px]">

            {{-- portada --}}
            <div class="flex flex-col items-center">
                @if($catalogo->foto)
                    <img src="{{ $catalogo->foto }}" alt="{{ $catalogo->titulo_archivo }}" class="w-[234px] max-w-full h-auto object-cover">
                @endif
            </div>

            {{-- datos --}}
            <div class="lg:pr-[40px]">
                @if($catalogo->subtitulo_archivo)
                    <p class="text-[16px] leading-[1.5] text-[#086AFF]">{{ $catalogo->subtitulo_archivo }}</p>
                @endif
                @if($catalogo->titulo_archivo)
                    <h2 class="mt-[1px] text-[32px] lg:text-[40px] leading-[1.2] font-bold">{{ $catalogo->titulo_archivo }}</h2>
                @endif
                @if($catalogo->descripcion_archivo)
                    <div class="mt-[24px] max-w-[496px] text-[16px] leading-[1.5]">{!! $catalogo->descripcion_archivo !!}</div>
                @endif

                @if($catalogo->paginas || $peso || $actualizado)
                <div class="mt-[44px] flex flex-wrap gap-x-[24px] gap-y-[16px]">
                    @if($catalogo->paginas)
                    <div>
                        <p class="text-[24px] leading-[1.2] font-bold">{{ $catalogo->paginas }}</p>
                        <p class="text-[14px] leading-[1.5] text-[#DEE7EE]">PÁGINAS</p>
                    </div>
                    @endif
                    @if($peso)
                    <div>
                        <p class="text-[24px] leading-[1.2] font-bold">{{ $peso }}</p>
                        <p class="text-[14px] leading-[1.5] text-[#DEE7EE]">PESO DEL PDF</p>
                    </div>
                    @endif
                    @if($actualizado)
                    <div>
                        <p class="text-[24px] leading-[1.2] font-bold">{{ $actualizado }}</p>
                        <p class="text-[14px] leading-[1.5] text-[#DEE7EE]">ACTUALIZADO</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($catalogo->archivo)
                    <a href="{{ $catalogo->archivo }}" target="_blank" rel="noopener" download
                       class="mt-[44px] inline-flex items-center justify-center w-[182px] bg-[#086AFF] p-[10px] text-[16px] leading-[1.5] text-white">
                        DESCARGAR PDF
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- por categoría --}}
        @if($categorias->isNotEmpty())
        <div class="mt-[60px]">
            @if($catalogo->subtitulo2)
                <p class="text-[16px] leading-[1.5] text-[#086AFF]">{{ $catalogo->subtitulo2 }}</p>
            @endif
            @if($catalogo->titulo2)
                <h2 class="mt-[1px] text-[28px] lg:text-[32px] leading-[1.2] font-bold text-[#003879]">{{ $catalogo->titulo2 }}</h2>
            @endif

            <div id="categorias-carrusel"
                 class="mt-[24px] flex gap-[24px] overflow-x-auto snap-x snap-mandatory scroll-smooth"
                 style="scrollbar-width:none">
                @foreach($categorias as $categoria)
                    <div class="snap-start shrink-0 w-full sm:w-[calc((100%-24px)/2)] lg:w-[calc((100%-72px)/4)] min-h-[166px] bg-white border border-[#DEDFE0] p-[24px] flex flex-col justify-between">
                        <h3 class="text-[24px] w-1/2 leading-[1.2] font-bold text-[#003879]">{{ $categoria['nombre'] }}</h3>
                        @if(!empty($categoria['archivo']))
                            <a href="{{ $categoria['archivo'] }}" target="_blank" rel="noopener" download
                               class="mt-[24px] inline-flex items-center gap-[8px] text-[14px] leading-[1.5] text-[#086AFF]">
                                DESCARGAR
                                <img src="{{ asset('assets/img/catalogo/arrow-right.svg') }}" alt="" class="w-[16px] h-[16px]">
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <div id="categorias-indicadores" class="mt-[32px] flex justify-center gap-[6px]"></div>
        </div>
        @endif

    </div>
</div>

<style>
    #categorias-carrusel::-webkit-scrollbar { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carrusel = document.getElementById('categorias-carrusel');
    const indicadores = document.getElementById('categorias-indicadores');
    if (!carrusel || !indicadores) return;

    const GAP = 24;
    let paginas = 1;

    // Máximo desplazamiento posible: la última página casi nunca es una "página completa"
    function maxScroll() {
        return Math.max(0, carrusel.scrollWidth - carrusel.clientWidth);
    }

    function construir() {
        paginas = Math.max(1, Math.ceil((carrusel.scrollWidth - 2) / (carrusel.clientWidth + GAP)));
        indicadores.innerHTML = '';
        if (paginas < 2) return;

        for (let i = 0; i < paginas; i++) {
            const barra = document.createElement('button');
            barra.type = 'button';
            barra.setAttribute('aria-label', 'Ir a la página ' + (i + 1));
            barra.className = 'h-[5px] w-[30px] hover:scale-100';
            barra.addEventListener('click', function () {
                carrusel.scrollTo({ left: (i / (paginas - 1)) * maxScroll(), behavior: 'smooth' });
            });
            indicadores.appendChild(barra);
        }
        actualizar();
    }

    function actualizar() {
        const max = maxScroll();
        const actual = (paginas < 2 || max === 0)
            ? 0
            : Math.round((carrusel.scrollLeft / max) * (paginas - 1));
        Array.from(indicadores.children).forEach(function (barra, i) {
            barra.style.backgroundColor = i === actual ? '#086AFF' : '#DEDFE0';
        });
    }

    carrusel.addEventListener('scroll', actualizar, { passive: true });
    window.addEventListener('resize', construir);
    construir();
});
</script>

@endsection
