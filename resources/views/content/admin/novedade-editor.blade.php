@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Editar Novedad</h3>
        <hr class="mx-6">
    </div>
    
    {{-- Formulario --}}
    <div class="mx-20 pt-6" id="app">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar novedad</h3>
            </div>
            <hr class="my-3">
            
            <form method="POST"
                  action="{{ route('adm.novedade-update') }}"
                  enctype="multipart/form-data"
                  @submit.prevent="handleSubmit">
                @csrf

                <input type="hidden" name="novedade_id" value="{{ $novedade->id ?? '' }}">

                {{-- Foto --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Foto (recomendado 600x475) / (2MB máximo)
                    </label>

                    @if (!empty($novedade->foto))
                        <img :src="foto.foto || '{{ $novedade->foto }}'"
                             class="mb-3 w-full max-w-[600px] h-[475px] object-cover">
                    @endif

                    <input type="file" name="foto" @change="subirFoto"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Orden --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Orden</label>
                    <input type="text" name="orden" value="{{ $novedade->orden ?? '' }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto chico --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto chico</label>
                    <input type="text" name="texto_chico" value="{{ $novedade->texto_chico ?? '' }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Título --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título</label>
                    <input type="text" name="titulo" value="{{ $novedade->titulo ?? '' }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto</label>
                    <textarea name="texto" rows="6"
                              class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{!! $novedade->texto ?? '' !!}</textarea>
                </div>

                <button type="submit" class="border rounded p-4 bg-white">
                    Actualizar
                </button>
            </form>
        </div>            
    </div>

@endsection

@push('scripts')
@include('includes.ckeditor-init')
@endpush
