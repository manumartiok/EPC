@php
    $listas = App\Models\Lista::where('active', true)->orderBy('orden')->get();
@endphp

@extends('layouts.layout-clientes')

@section('title', 'Lista de precios')

@section('content') 
{{-- contenido  --}}
<div class="pt-[10px]  pb-[180px] h-full jakarta max-w-[1366px] mx-auto px-[5%] md:px-[71px]">
    {{-- bread  --}}
    <div class="text-[12px] text-[#74716A]">
        <h3><a href="{{route('home')}}" class="font-bold">Inicio</a> > Lista de precios</h3>
    </div>
    {{-- tabla  --}}
    <div class="mt-[40px] bg-white rounded-[4px] overflow-x-auto">

        {{-- Header --}}
        <div class="min-w-[1100px] grid grid-cols-12 bg-[#A60000] text-white text-[16px] font-medium py-4  ">
            <div class="col-span-1"></div>
            <div class="col-span-4 ml-[70px]">Nombre</div>
            <div class="col-span-2 text-center">Formato</div>
            <div class="col-span-2 text-center">Peso</div>
            <div class="col-span-3 text-center"></div>
        </div>

        {{-- Rows --}}
        @foreach($listas as $item)
            @php
                $extension = strtoupper(pathinfo($item->archivo, PATHINFO_EXTENSION));
                $size = file_exists(public_path($item->archivo))
                        ? round(filesize(public_path($item->archivo)) / 1024) . 'kb'
                        : '-';
            @endphp

            <div class="min-w-[1100px] grid grid-cols-12 items-center  py-2 border-b text-[16px] text-[#4A4A4A]">

                <div class="col-span-1  flex justify-center items-center h-[80px] w-[80px]  bg-[#D9D9D9] rounded-[6px] ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
  <path d="M25.0827 3.58301V10.7497C25.0827 11.7 25.4602 12.6115 26.1322 13.2835C26.8042 13.9555 27.7157 14.333 28.666 14.333H35.8327M17.916 16.1247H14.3327M28.666 23.2913H14.3327M28.666 30.458H14.3327M26.8743 3.58301H10.7493C9.79899 3.58301 8.88755 3.96054 8.21555 4.63254C7.54354 5.30455 7.16602 6.21598 7.16602 7.16634V35.833C7.16602 36.7834 7.54354 37.6948 8.21555 38.3668C8.88755 39.0388 9.79899 39.4163 10.7493 39.4163H32.2493C33.1997 39.4163 34.1111 39.0388 34.7831 38.3668C35.4552 37.6948 35.8327 36.7834 35.8327 35.833V12.5413L26.8743 3.58301Z" stroke="#A60000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                </div>
                {{-- Nombre --}}
                <div class="col-span-4 flex items-center ml-[60px] text-[#5C5C5C]">
                    <span>{{ $item->nombre }}</span>
                </div>

                {{-- Formato --}}
                <div class="col-span-2 text-center">
                    {{ $extension }}
                </div>

                {{-- Peso --}}
                <div class="col-span-2 text-center">
                    {{ $size }}
                </div>

                {{-- Acciones --}}
                <div class="col-span-3 flex gap-3 justify-center">
                    <a href="{{ asset($item->archivo) }}"
                    target="_blank"
                    class=" h-[46px] w-[138px] flex justify-center items-center text-[#DE2222] font-bold rounded-[6px] text-[16px] border border-[#DE2222]">
                        Ver online
                    </a>
                    <a href="{{ asset($item->archivo) }}"
                    download
                    class="border h-[46px] w-[138px] flex justify-center items-center bg-[#DE2222] text-white font-bold  rounded-[6px] text-[16px] ">
                        Descargar
                    </a>

                </div>

            </div>
        @endforeach

    </div>
</div>
 
@endsection

