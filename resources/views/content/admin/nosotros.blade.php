@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div id="app">
    {{-- cabezal  --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Nosotros</h3>
        <hr class="mx-6">
    </div>
    
    {{-- Editor --}}
    <div class="mx-20 pt-6" >
        <div class="border rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold  text-gray-600">Editar Nosotros</h3>
            </div>
            <hr class="my-3">
            
            <form method="POST" action="{{ route('adm.nosotros-update') }}" enctype="multipart/form-data" @submit.prevent="handleSubmit">
                @csrf
                <input type="hidden" name="nosotro_id" value="{{ $nosotro->id ?? '' }}">

                <div>
                    {{-- Foto principal --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="foto">
                            Foto (recomendado 600x400) / (2MB tamaño máximo)
                        </label>
                        
                        @if (!empty($nosotro->foto))
                            <img :src="foto.foto || '{{ $nosotro->foto }}'" alt="Foto" class="mb-3 w-full max-w-[300px] max-h-[200px] object-cover">
                        @endif
                        
                        <input type="file" name="foto" id="foto" @change="subirFoto"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    {{-- Título 1 / Texto 1 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título 1
                        </label>
                        <input type="text" name="titulo1"
                            value="{{ $nosotro->titulo1 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Texto 1
                        </label>
                        <input type="text" name="texto1"
                            value="{{ $nosotro->texto1 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Título 2 / Texto 2 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título 2
                        </label>
                        <input type="text" name="titulo2"
                            value="{{ $nosotro->titulo2 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Texto 2
                        </label>
                        <input type="text" name="texto2"
                            value="{{ $nosotro->texto2 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Título 3 / Texto 3 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Título 3</label>
                        <input type="text" name="titulo3"
                            value="{{ $nosotro->titulo3 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Texto 3</label>
                        <input type="text" name="texto3"
                            value="{{ $nosotro->texto3 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Título 4 / Texto 4 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Título 4</label>
                        <input type="text" name="titulo4"
                            value="{{ $nosotro->titulo4 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Texto 4</label>
                        <input type="text" name="texto4"
                            value="{{ $nosotro->texto4 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Jaula 1 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula título 1</label>
                        <input type="text" name="jaula_titulo1"
                            value="{{ $nosotro->jaula_titulo1 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula descripción 1</label>
                        <textarea name="jaula_descripcion1" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->jaula_descripcion1 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Jaula 2 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula título 2</label>
                        <input type="text" name="jaula_titulo2"
                            value="{{ $nosotro->jaula_titulo2 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula descripción 2</label>
                        <textarea name="jaula_descripcion2" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->jaula_descripcion2 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Jaula 3 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula título 3</label>
                        <input type="text" name="jaula_titulo3"
                            value="{{ $nosotro->jaula_titulo3 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula descripción 3</label>
                        <textarea name="jaula_descripcion3" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->jaula_descripcion3 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Jaula 4 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula título 4</label>
                        <input type="text" name="jaula_titulo4"
                            value="{{ $nosotro->jaula_titulo4 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Jaula descripción 4</label>
                        <textarea name="jaula_descripcion4" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->jaula_descripcion4 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Descripción (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->descripcion ?? '' !!}
                        </textarea>
                    </div>


                    {{-- Logo 1 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="icono1">
                            Logo 1 (recomendado 60x60) / (2MB tamaño máximo)
                        </label>
                        @if (!empty($nosotro->icono1))
                            <img :src="foto.icono1 || '{{ $nosotro->icono1 }}'" alt="Logo 1" class="mb-3 w-full max-w-[60px] h-[60px] object-cover">
                        @endif
                        <input type="file" name="icono1" id="icono1" @change="subirFoto"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    {{-- Título logo 1 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título logo 1
                        </label>
                        <input type="text" name="nombre_icono1"
                            value="{{ $nosotro->nombre_icono1 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Texto logo 1 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Texto logo 1
                        </label>
                        <textarea name="texto_icono1" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->texto_icono1 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Logo 2 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="icono2">
                            Logo 2 (recomendado 60x60) / (2MB tamaño máximo)
                        </label>
                        @if (!empty($nosotro->icono2))
                            <img :src="foto.icono2 || '{{ $nosotro->icono2 }}'" alt="Logo 2" class="mb-3 w-full max-w-[60px] h-[60px] object-cover">
                        @endif
                        <input type="file" name="icono2" id="icono2" @change="subirFoto"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    {{-- Título logo 2 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título logo 2
                        </label>
                        <input type="text" name="nombre_icono2"
                            value="{{ $nosotro->nombre_icono2 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Texto logo 2 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Texto logo 2
                        </label>
                        <textarea name="texto_icono2" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->texto_icono2 ?? '' !!}
                        </textarea>
                    </div>

                    {{-- Logo 3 --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="icono3">
                            Logo 3 (recomendado 60x60) / (2MB tamaño máximo)
                        </label>
                        @if (!empty($nosotro->icono3))
                            <img :src="foto.icono3 || '{{ $nosotro->icono3 }}'" alt="Logo 3" class="mb-3 w-full max-w-[60px] h-[60px] object-cover">
                        @endif
                        <input type="file" name="icono3" id="icono3" @change="subirFoto"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    {{-- Título logo 3 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título logo 3
                        </label>
                        <input type="text" name="nombre_icono3"
                            value="{{ $nosotro->nombre_icono3 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Texto logo 3 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Texto logo 3
                        </label>
                        <textarea name="texto_icono3" class="ckeditor w-full rounded" rows="6">
                            {!! $nosotro->texto_icono3 ?? '' !!}
                        </textarea>
                    </div>
                </div>
                
                <button type="submit" class="border rounded p-4 bg-white">Actualizar</button>
            </form>
        </div>            
    </div>
</div>
@endsection

@push('scripts')
@include('includes.ckeditor-init')
@endpush