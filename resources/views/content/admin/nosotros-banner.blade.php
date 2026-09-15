@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Editar banner Nosotros</h3>
        <hr class="mx-6">
    </div>
    
    {{-- Editor --}}
    <div class="mx-20 pt-6" id="app">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold  text-gray-600">Editar banner</h3>
            </div>
            <hr class="my-3">
            
            <form method="POST" action="{{ route('adm.nosotros-ban-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="bn_id" value="{{ $bn->id ?? '' }}">

                <div>
                    <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="foto">Foto (recomendado 1366x300) / (2MB tamaño máximo)</label>

                    @if (!empty($bn->foto))
                        <img :src="foto.foto || '{{ $bn->foto }}'" alt="Foto"  class="mb-3 w-full max-w-[1366px] h-[300px] object-cover">
                    @endif

                    <input type="file" name="foto" id="foto" @change="subirFoto"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="texto">Título</label>
                        <input type="text" id="texto" name="texto" placeholder="Título" value="{{ $bn->texto ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit" class="border rounded p-4 bg-white">Actualizar</button>
            </form>
        </div>            
    </div>
@endsection