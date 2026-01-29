@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Crear Novedad</h3>
        <hr class="mx-6">
    </div>
    
    {{-- Formulario --}}
    <div class="mx-20 pt-6">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Nueva novedad</h3>
            </div>
            <hr class="my-3">
            
            <form method="POST"
                  action="{{ route('adm.novedade-store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Foto (recomendado 600x475) / (2MB máximo)
                    </label>
                    <input type="file" name="foto"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Orden</label>
                    <input type="text" name="orden"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto chico</label>
                    <input type="text" name="texto_chico"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título</label>
                    <input type="text" name="titulo"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto</label>
                    <textarea name="texto" rows="6"
                              class="ckeditor w-full border border-gray-300 rounded px-4 py-2"></textarea>
                </div>

                <button type="submit" class="border rounded p-4 bg-white">
                    Crear
                </button>
            </form>
        </div>            
    </div>

@endsection

@push('scripts')
@include('includes.ckeditor-init')
@endpush
