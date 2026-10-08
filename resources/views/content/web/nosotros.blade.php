@php
    $nosotros = App\Models\Nosotro::first() ?? new App\Models\Nosotro();
    $jaulas = collect(range(1, 4))->map(fn ($i) => [
        'titulo'      => $nosotros->{'jaula_titulo' . $i},
        'descripcion' => $nosotros->{'jaula_descripcion' . $i},
    ])->filter(fn ($j) => $j['titulo'] || $j['descripcion']);
    $valores = collect(range(1, 3))->map(fn ($i) => [
        'icono'       => $nosotros->{'icono' . $i},
        'titulo'      => $nosotros->{'nombre_icono' . $i},
        'descripcion' => $nosotros->{'texto_icono' . $i},
    ])->filter(fn ($v) => $v['icono'] || $v['titulo'] || $v['descripcion']);
@endphp

@extends('layouts.web')

@section('title', 'Nosotros')

@section('content')

<div class="letra bg-[#F2F5F8]">

    {{-- nosotros --}}
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] pt-[24px] pb-[80px]">
        <h3 class="text-[12px] text-[#303030]"><span class="font-bold">Inicio</span> > Nosotros</h3>

        <div class="flex flex-col lg:flex-row gap-[40px] lg:gap-[24px] mt-[72px]">
            {{-- texto --}}
            <div class="w-full lg:w-[600px] flex flex-col">
                @if($nosotros->titulo1)
                    <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $nosotros->titulo1 }}</p>
                @endif
                @if($nosotros->texto1)
                    <h1 class="text-[32px] md:text-[40px] leading-[1.2] font-bold text-[#003879] mb-[24px]">{{ $nosotros->texto1 }}</h1>
                @endif
                <div class="text-[16px] leading-[1.5] text-[#303030] [&_p]:mb-0">
                    {!! $nosotros->descripcion !!}
                </div>
            </div>
            {{-- foto --}}
            @if($nosotros->foto)
                <div class="w-full lg:w-[600px] lg:ml-auto flex-shrink-0">
                    <img src="{{ $nosotros->foto }}" alt="{{ $nosotros->texto1 }}" class="w-full aspect-[3/2] object-cover">
                </div>
            @endif
        </div>
    </div>

    {{-- como trabajamos --}}
    @if($jaulas->isNotEmpty())
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] pb-[80px]">
        @if($nosotros->titulo2)
            <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $nosotros->titulo2 }}</p>
        @endif
        @if($nosotros->texto2)
            <h2 class="text-[32px] md:text-[40px] leading-[1.2] font-bold text-[#003879] mb-[24px]">{{ $nosotros->texto2 }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 bg-white border border-[#DEDFE0] divide-y md:divide-y-0 md:divide-x divide-[#DEDFE0]">
            @foreach($jaulas as $jaula)
                <div class="px-[24px] pt-[47px] pb-[24px] min-h-[203px]">
                    <h3 class="text-[16px] leading-[1.2] font-bold text-[#003879] mb-[11px]">{{ $jaula['titulo'] }}</h3>
                    <div class="text-[14px] leading-[1.5] text-[#303030] [&_p]:mb-0">{!! $jaula['descripcion'] !!}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- valores --}}
    @if($valores->isNotEmpty())
    <div class="w-full bg-[#DEE7EE]">
        <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] pt-[60px] pb-[80px]">
            @if($nosotros->titulo4)
                <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $nosotros->titulo4 }}</p>
            @endif
            @if($nosotros->texto4)
                <h2 class="text-[32px] md:text-[40px] leading-[1.2] font-bold text-[#003879] mb-[24px]">{{ $nosotros->texto4 }}</h2>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 bg-white border border-[#DEDFE0] divide-y lg:divide-y-0 lg:divide-x divide-[#DEDFE0]">
                @foreach($valores as $valor)
                    <div class="px-[24px] pt-[53px] pb-[24px] min-h-[250px] flex flex-col">
                        @if($valor['icono'])
                            <img src="{{ $valor['icono'] }}" alt="{{ $valor['titulo'] }}" class="w-[44px] h-[44px] object-contain mb-[30px]">
                        @endif
                        <h3 class="text-[20px] leading-[1.2] font-bold text-[#003879] mb-[12px]">{{ $valor['titulo'] }}</h3>
                        <div class="text-[14px] leading-[1.5] text-black [&_p]:mb-0">{!! $valor['descripcion'] !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@endsection
