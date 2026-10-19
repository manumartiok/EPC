@extends('layouts.master')

@section('title', 'Home')

@section('content')

{{-- Cabezal --}}
<div>
    <h3 class="text-[20px] font-bold text-gray-500">Home</h3>
    <hr class="mx-6">
</div>

@php
    $inputClass = 'w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500';

    $secciones = [
        'Sección 1' => [
            'texto_chico1' => 'Texto chico 1',
            'titulo1'      => 'Título 1',
        ],
        'Sección 2' => [
            'texto_chico2' => 'Texto chico 2',
            'titulo2'      => 'Título 2',
        ],
        'Sección 3' => [
            'texto_chico3' => 'Texto chico 3',
            'titulo3'      => 'Título 3',
            'subtext3'     => 'Subtexto 3',
        ],
        'Sección 4' => [
            'texto_chico4' => 'Texto chico 4',
            'titulo4'      => 'Título 4',
        ],
        'Sección 5' => [
            'texto5' => 'Texto 5',
        ],
    ];
@endphp

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

            @foreach ($secciones as $nombre => $campos)
                <h4 class="text-[16px] font-semibold text-gray-600 mt-6 mb-3">{{ $nombre }}</h4>

                {{-- Foto 1 y descripción pertenecen a la sección 1 --}}
                @if ($nombre === 'Sección 1')
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="foto1">
                            Foto 1 (2MB máximo)
                        </label>

                        <img src="{{ $home->foto1 }}"
                             class="mb-3 w-full max-w-[600px] object-cover rounded {{ empty($home->foto1) ? 'hidden' : '' }}"
                             id="preview_foto1">

                        <input type="file"
                               name="foto1"
                               id="foto1"
                               accept="image/*"
                               onchange="previewImage(this, 'preview_foto1')"
                               class="{{ $inputClass }}">

                        @error('foto1')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                @foreach ($campos as $campo => $label)
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="{{ $campo }}">
                            {{ $label }}
                        </label>
                        <input type="text"
                               id="{{ $campo }}"
                               name="{{ $campo }}"
                               value="{{ old($campo, $home->$campo) }}"
                               class="{{ $inputClass }}">

                        @error($campo)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                @if ($nombre === 'Sección 1')
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
                @endif
            @endforeach

            <div class="flex gap-4 mt-6">
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
