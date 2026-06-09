@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

{{-- cabezal --}}
<div>
    <h3 class="text-[20px] font-bold text-gray-500">Editar Lista</h3>
    <hr class="mx-6">
</div>

{{-- Formulario --}}
<div class="mx-20 pt-6">
    <div class="border border-rounded p-4">
        <div>
            <h3 class="text-[18px] font-semibold text-gray-600">Editar lista</h3>
        </div>
        <hr class="my-3">

        <form method="POST"
              action="{{ route('adm.lista-update') }}"
              enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="lista_id" value="{{ $lista->id ?? '' }}">

            {{-- Archivo --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">
                    Archivo (PDF, Excel, imagen, etc. / 10MB máximo) 
                </label>

                @if (!empty($lista->archivo))
                    <a href="{{ asset($lista->archivo) }}"
                       target="_blank"
                       class="block mb-3 text-blue-600 underline">
                        Ver archivo actual
                    </a>
                @endif

                <input type="file"
                       name="archivo"
                       class="w-full border border-gray-300 rounded px-4 py-2">
            </div>

            {{-- Orden --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Orden</label>
                <input type="text"
                       name="orden"
                       value="{{ $lista->orden ?? '' }}"
                       class="w-full border border-gray-300 rounded px-4 py-2">
            </div>

            {{-- Nombre --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                <input type="text"
                       name="nombre"
                       value="{{ $lista->nombre ?? '' }}"
                       class="w-full border border-gray-300 rounded px-4 py-2">
            </div>

            <button type="submit" class="border rounded p-4 bg-white">
                Actualizar
            </button>
        </form>
    </div>
</div>

@endsection
