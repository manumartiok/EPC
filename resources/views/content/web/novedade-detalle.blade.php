
@extends('layouts.web')

@section('title', $novedade->titulo)

@section('content')

{{-- contenido --}}
<div class="h-auto letra mx-auto px-[5%] md:px-[71px] max-w-[1366px] pt-[80px] pb-[110px]">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-[48px]">

        {{-- imagen --}}
        <div class="w-full">
            <img src="{{ $novedade->foto }}"
                 class="w-full h-[300px] md:h-[420px] object-cover border border-[#DDDDE0] rounded-[12px]"
                 alt="{{ $novedade->titulo }}">
        </div>

        {{-- texto --}}
        <div class="flex flex-col justify-center">

            <h4 class="text-[14px] font-bold text-[#00B1F6] mb-[8px]">
                {{ $novedade->texto_chico }}
            </h4>

            <h2 class="text-[32px] font-semibold mb-[16px]">
                {{ $novedade->titulo }}
            </h2>

            <div class="text-[16px] text-[#74716A] leading-relaxed">
                {!! $novedade->texto !!}
            </div>

        </div>

    </div>

</div>

@endsection
