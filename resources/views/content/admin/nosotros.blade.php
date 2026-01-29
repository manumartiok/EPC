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
                    {{-- VIDEO --}}
                    <div class="mb-4">
                        <label class="form-label text-gray-700 font-medium mb-2">Video (recomendado 1224x575 | 50MB máximo)</label>
                        @if (!empty($nosotro->video))
                            <video autoplay loop muted playsinline class="mb-3 mt-2 w-full max-w-[1224px] h-[575px] object-cover mx-auto">
                                <source src="{{ $nosotro->video }}" type="video/mp4">
                                Tu navegador no soporta el video.
                            </video>
                        @endif  
                        <input type="file" name="video" class="form-control" accept="video/*" id="video"/>
                    </div>
                    {{-- Foto principal --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="foto">
                            Foto (recomendado 600x480) / (2MB tamaño máximo)
                        </label>
                        
                        @if (!empty($nosotro->foto))
                            <img :src="foto.foto || '{{ $nosotro->foto }}'" alt="Foto" class="mb-3 w-full max-w-[300px] max-h-[240px] object-cover">
                        @endif
                        
                        <input type="file" name="foto" id="foto" @change="subirFoto"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    {{-- Título 1 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título 1
                        </label>
                        <input type="text" name="texto1"
                            value="{{ $nosotro->texto1 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>

                    {{-- Título 2 (directo) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            Título 2
                        </label>
                        <input type="text" name="texto2"
                            value="{{ $nosotro->texto2 ?? '' }}"
                            class="w-full border border-gray-300 rounded px-4 py-2">
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