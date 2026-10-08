@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Editar Motivo de consulta</h3>
        <hr class="mx-6">
    </div>

    {{-- Formulario --}}
    <div class="mx-20 pt-6">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar motivo de consulta</h3>
            </div>
            <hr class="my-3">

            <form method="POST" action="{{ route('adm.consulta-update') }}">
                @csrf

                <input type="hidden" name="consulta_id" value="{{ $consulta->id ?? '' }}">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Orden --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Orden</label>
                    <input type="text" name="orden" value="{{ old('orden', $consulta->orden ?? '') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Motivo --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Motivo</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $consulta->nombre ?? '') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">
                    Actualizar
                </button>
            </form>
        </div>
    </div>

@endsection
