@php
    $novedades = App\Models\Novedade::where('active', true)
        ->orderBy('orden')
        ->get();

    // Categorías: agrupamos por texto_chico y contamos coincidencias
    $categorias = $novedades
        ->groupBy('texto_chico')
        ->map(function ($grupo, $nombre) {
            return ['nombre' => $nombre, 'cantidad' => $grupo->count()];
        })
        ->values();

    // Archivo: agrupamos por mes de creación y contamos coincidencias
    $mesesEs = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $archivo = $novedades
        ->groupBy(fn($item) => $item->created_at->format('n'))
        ->map(function ($grupo, $mes) use ($mesesEs) {
            return [
                'nombre'   => $mesesEs[(int) $mes],
                'cantidad' => $grupo->count(),
                'mes'      => (int) $mes,
            ];
        })
        ->sortBy('mes')
        ->values();
@endphp

@extends('layouts.web')

@section('title', 'Novedades')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Nunito+Sans:wght@400;700&family=Sora:wght@700&display=swap" rel="stylesheet">

<div class="letra text-[#303030]">

    <div class="bg-[#F2F5F8]">
        <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[110px]">
            <p class="text-[12px] leading-[1.5]"><a href="{{ route('home') }}" class="font-bold">Inicio</a> &gt; Novedades</p>

            <div class="mt-[70px]">
                <p class="text-[16px] leading-[1.5] text-[#086AFF]">NOVEDADES</p>
                <h1 class="mt-[1px] text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">Enterate de las últimas novedades</h1>
                <p class="mt-[12px] text-[16px] leading-[1.5]">Lanzamientos, nuevas líneas y novedades del sector.</p>
            </div>

        <div class="mt-[32px] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">

            {{-- Filtros: primer item del grid, alto máx 530px en desktop --}}
            <aside class="w-full md:max-h-[530px] flex flex-col overflow-y-auto">

                {{-- Buscador --}}
                <div class="flex items-center justify-between h-[46px] bg-white border border-[#ECEBEB] rounded-[4px] px-[16px]">
                    <input type="text" id="buscador-novedades" placeholder="Buscar"
                           class="w-full text-[16px] text-[#333] placeholder-[#333] outline-none bg-transparent" style="font-family:'Public Sans','Inter',sans-serif">
                    <img src="{{ asset('assets/img/novedades/search.svg') }}" alt="" class="w-[24px] h-[24px] shrink-0">
                </div>

                {{-- Categorías --}}
                <div class="mt-[34px]">
                    <button type="button" data-target="panel-categorias"
                            class="toggle-btn hover:scale-100 w-full h-[46px] flex items-center justify-between bg-[#F9F9F9] rounded-[4px] px-[16px]">
                        <span class="text-[20px] leading-[1.2] font-bold text-[#17181B]" style="font-family:'Sora',sans-serif">Categorías</span>
                        <img src="{{ asset('assets/img/novedades/chevron.svg') }}" alt="" class="toggle-icon w-[24px] h-[24px] rotate-180 transition-transform duration-200">
                    </button>

                    <div id="panel-categorias" class="flex flex-col">
                        @forelse($categorias as $categoria)
                            <button type="button"
                                    class="filter-option hover:scale-100 w-full h-[40px] flex items-center justify-between px-[16px] border-b border-[#ECEBEB] text-left text-[16px] text-[#333]"
                                    data-filter-kind="categoria"
                                    data-filter-value="{{ Str::lower($categoria['nombre']) }}"
                                    style="font-family:'Inter',sans-serif">
                                <span>{{ $categoria['nombre'] }}</span>
                                <span class="font-semibold">{{ $categoria['cantidad'] }}</span>
                            </button>
                        @empty
                            <div class="px-[16px] py-[10px] text-[14px] text-[#333]/50">Sin categorías</div>
                        @endforelse
                    </div>
                </div>

                {{-- Archivo --}}
                <div class="mt-[64px]">
                    <button type="button" data-target="panel-archivo"
                            class="toggle-btn hover:scale-100 w-full h-[46px] flex items-center justify-between bg-[#F9F9F9] rounded-[4px] px-[16px]">
                        <span class="text-[20px] leading-[1.2] font-bold text-[#17181B]" style="font-family:'Sora',sans-serif">Archivo</span>
                        <img src="{{ asset('assets/img/novedades/chevron.svg') }}" alt="" class="toggle-icon w-[24px] h-[24px] rotate-180 transition-transform duration-200">
                    </button>

                    <div id="panel-archivo" class="flex flex-col">
                        @forelse($archivo as $mes)
                            <button type="button"
                                    class="filter-option hover:scale-100 w-full h-[40px] flex items-center justify-between px-[16px] border-b border-[#ECEBEB] text-left text-[16px] text-[#333]"
                                    data-filter-kind="mes"
                                    data-filter-value="{{ $mes['mes'] }}"
                                    style="font-family:'Inter',sans-serif">
                                <span>{{ $mes['nombre'] }}</span>
                                <span class="font-semibold">{{ $mes['cantidad'] }}</span>
                            </button>
                        @empty
                            <div class="px-[16px] py-[10px] text-[14px] text-[#333]/50">Sin registros</div>
                        @endforelse
                    </div>
                </div>
            </aside>

            @foreach($novedades as $item)
                @include('content.web.partials.novedad-card', ['item' => $item])
            @endforeach

        </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Toggle Categorías / Archivo
    document.querySelectorAll('.toggle-btn').forEach(function (btn) {
        const panel = document.getElementById(btn.dataset.target);
        const icon = btn.querySelector('.toggle-icon');

        btn.addEventListener('click', function () {
            const isHidden = panel.classList.toggle('hidden');
            icon.classList.toggle('rotate-180', !isHidden);
        });
    });

    // Buscador y filtros de categoría/archivo
    const input = document.getElementById('buscador-novedades');
    const cards = document.querySelectorAll('.novedad-card');
    const filterOptions = document.querySelectorAll('.filter-option');
    const filtros = {
        categoria: '',
        mes: ''
    };

    function actualizarTarjetas() {
        const query = input.value.trim().toLowerCase();

        cards.forEach(function (card) {
            const titulo = card.dataset.titulo || '';
            const textoChico = card.dataset.textoChico || '';
            const coincideBusqueda = titulo.includes(query) || textoChico.includes(query);
            const coincideCategoria = !filtros.categoria || card.dataset.categoria === filtros.categoria;
            const coincideMes = !filtros.mes || card.dataset.mes === filtros.mes;

            card.classList.toggle('hidden', !(coincideBusqueda && coincideCategoria && coincideMes));
        });
    }

    input.addEventListener('input', actualizarTarjetas);

    filterOptions.forEach(function (option) {
        option.addEventListener('click', function () {
            const kind = option.dataset.filterKind;
            const value = option.dataset.filterValue;
            const isActive = filtros[kind] === value;

            filtros[kind] = isActive ? '' : value;
            filterOptions.forEach(function (item) {
                item.classList.toggle('bg-[#F9F9F9]',
                    filtros[item.dataset.filterKind] === item.dataset.filterValue);
            });
            actualizarTarjetas();
        });
    });
});
</script>

@endsection
