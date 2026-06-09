@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Crear Producto</h3>
        <hr class="mx-6">
    </div>
    
    {{-- Formulario --}}
    <div class="mx-20 pt-6">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Nuevo producto</h3>
            </div>
            <hr class="my-3">
            
            <form method="POST"
                  action="{{ route('adm.producto-store') }}"
                  enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Marca</label>
                    <select name="marca_id" class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">-- Seleccionar --</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Foto (recomendado 510x400) / (2MB máximo)
                    </label>
                          <input type="file" name="foto"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Archivo (opcional)</label>
                          <input type="file" name="archivo"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Orden</label>
                          <input type="text" name="orden" value="{{ old('orden') }}"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Código</label>
                          <input type="text" name="codigo" value="{{ old('codigo') }}"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                          <input type="text" name="nombre" value="{{ old('nombre') }}"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Precio</label>
                          <input type="number" step="0.01" name="precio" value="{{ old('precio') }}"
                              class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="destacado" value="1" class="mr-2" checked>
                        <span class="text-gray-700">Destacado</span>
                    </label>
                </div>

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">
                    Crear
                </button>
            </form>
        </div>            
    </div>

    <hr class="my-10">

    {{-- GALERÍA: indicar guardar producto primero --}}
    <div class="mx-20">
        <div class="border border-rounded p-4 bg-white">
            <h3 class="text-[18px] font-semibold text-gray-600 mb-3">Galería de fotos</h3>
            <p class="text-sm text-gray-500">Guarda el producto primero para poder agregar fotos a la galería.</p>
        </div>
    </div>

@endsection
