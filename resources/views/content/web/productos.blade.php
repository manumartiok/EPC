@extends('layouts.web')

@section('title', 'Productos')

@section('content')

<div class="letra bg-[#F2F5F8] text-[#303030]">
    <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[120px]">

        {{-- breadcrumb --}}
        <p class="text-[12px] leading-[1.5]"><a href="{{ route('home') }}" class="font-bold">Inicio</a> &gt; Productos</p>

        {{-- encabezado + buscador --}}
        <div class="mt-[70px] flex flex-col lg:flex-row lg:items-end lg:justify-between gap-[24px]">
            <div>
                <p class="text-[16px] leading-[1.5] text-[#086AFF]">CATÁLOGO COMPLETO</p>
                <h1 class="mt-[1px] text-[32px] lg:text-[40px] leading-[1.2] font-bold text-black">Productos</h1>
            </div>

            <form method="GET" action="{{ route('productos') }}"
                  class="flex items-center gap-[8px] w-full lg:w-[288px] h-[50px] bg-white border border-[#DEDFE0] px-[24px]">
                <button type="submit" aria-label="Buscar" class="shrink-0 hover:scale-100">
                    <img src="{{ asset('assets/img/productos/search.svg') }}" alt="" class="w-[24px] h-[24px]">
                </button>
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por productos"
                       class="w-full min-w-0 text-[14px] leading-[1.5] text-[#303030] placeholder:text-[#303030]/50 bg-transparent outline-none">
            </form>
        </div>

        {{-- productos --}}
        <div class="mt-[32px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px]">
            @forelse($productos as $producto)
                <a href="{{ route('productos.detalle', ['id' => $producto->id]) }}"
                   class="bg-white border border-[#DEDFE0] flex flex-col">
                    <div class="w-full h-[230px] overflow-hidden flex items-center justify-center bg-white">
                        @if(!empty($producto->foto))
                            <img src="{{ asset($producto->foto) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain">
                        @endif
                    </div>

                    <div class="mx-[24px] border-t border-[#DEDFE0] flex items-center justify-between gap-[12px] h-[60px]">
                        <h3 class="text-[16px] leading-[1.2] font-bold text-[#003879]">{{ $producto->nombre }}</h3>
                        <img src="{{ asset('assets/img/productos/arrow-right.svg') }}" alt="" class="w-[16px] h-[16px] shrink-0">
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white border border-[#DEDFE0] p-[24px]">
                    <p class="text-[16px] leading-[1.5]">No se encontraron productos.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
