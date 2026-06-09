@extends('layouts.master')

@section('title', 'Home')

@section('content')

{{-- Cabezal --}}
<div>
    <h3 class="text-[20px] font-bold text-gray-500">Home</h3>
    <hr class="mx-6">
</div>

{{-- Editor --}}
<div class="mx-20 pt-6">
    <div class="border rounded p-4">
        <div>
            <h3 class="text-[18px] font-semibold text-gray-600">Editar Home</h3>
        </div>

        <hr class="my-3">

        <form method="POST"
              action="{{ route('adm.home-update') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- Título 1 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="titulo1">
                    Título 1
                </label>
                <input type="text"
                       id="titulo1"
                       name="titulo1"
                       value="{{ old('titulo1', $home->titulo1) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('titulo1')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto 1 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto1">
                    Foto 1 (recomendado 1251x343) / (2MB máximo)
                </label>

                @if (!empty($home->foto1))
                    <img src="{{ $home->foto1 }}"
                         class="mb-3 w-full max-w-[1251px] max-h-[343px] object-cover rounded"
                         id="preview_foto1">
                @else
                    <img src=""
                         class="mb-3 w-full max-w-[1251px] max-h-[343px] object-cover rounded hidden"
                         id="preview_foto1">
                @endif

                <input type="file"
                       name="foto1"
                       id="foto1"
                       accept="image/*"
                       onchange="previewImage(this, 'preview_foto1')"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto1')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto 1 Texto 1 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto1_texto1">
                    Texto Foto 1 - Línea 1
                </label>
                <input type="text"
                       id="foto1_texto1"
                       name="foto1_texto1"
                       value="{{ old('foto1_texto1', $home->foto1_texto1) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto1_texto1')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto 1 Texto 2 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto1_texto2">
                    Texto Foto 1 - Línea 2
                </label>
                <input type="text"
                       id="foto1_texto2"
                       name="foto1_texto2"
                       value="{{ old('foto1_texto2', $home->foto1_texto2) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto1_texto2')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto 1 Texto 3 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto1_texto3">
                    Texto Foto 1 - Línea 3
                </label>
                <input type="text"
                       id="foto1_texto3"
                       name="foto1_texto3"
                       value="{{ old('foto1_texto3', $home->foto1_texto3) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto1_texto3')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Texto Botón --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto1_texto_boton">
                    Texto Botón
                </label>
                <input type="text"
                       id="foto1_texto_boton"
                       name="foto1_texto_boton"
                       value="{{ old('foto1_texto_boton', $home->foto1_texto_boton) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto1_texto_boton')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto 2 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="foto2">
                    Foto 2 (recomendado 673x600) / (2MB máximo)
                </label>

                @if (!empty($home->foto2))
                    <img src="{{ $home->foto2 }}"
                         class="mb-3 w-full max-w-[673px] max-h-[600px] object-cover rounded"
                         id="preview_foto2">
                @else
                    <img src=""
                         class="mb-3 w-full max-w-[673px] max-h-[600px] object-cover rounded hidden"
                         id="preview_foto2">
                @endif

                <input type="file"
                       name="foto2"
                       id="foto2"
                       accept="image/*"
                       onchange="previewImage(this, 'preview_foto2')"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('foto2')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Título 2 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="titulo2">
                    Título 2
                </label>

                <input type="text"
                       id="titulo2"
                       name="titulo2"
                       value="{{ old('titulo2', $home->titulo2) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('titulo2')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="descripcion">
                    Descripción
                </label>

                <textarea name="descripcion"
                          id="descripcion"
                          rows="4"
                          class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{!! old('descripcion', $home->descripcion) !!}</textarea>

                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Título 3 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="titulo3">
                    Título 3
                </label>

                <input type="text"
                       id="titulo3"
                       name="titulo3"
                       value="{{ old('titulo3', $home->titulo3) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('titulo3')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            

            <div class="flex gap-4">
                <button type="submit"
                        class="border rounded px-6 py-3 bg-white transition">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
    @include('includes.ckeditor-init')
@endpush