@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div id="app">
    {{-- cabezal  --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Catálogo</h3>
        <hr class="mx-6">
    </div>

    {{-- Editor --}}
    <div class="mx-20 pt-6">
        <div class="border rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar Catálogo</h3>
            </div>
            <hr class="my-3">

            <form method="POST" action="{{ route('adm.catalogo-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="catalogo_id" value="{{ $catalogo->id ?? '' }}">

                {{-- ENCABEZADO --}}
                <h4 class="text-[16px] font-semibold text-gray-600 mb-3">Encabezado</h4>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo 1 (texto chico sobre el título)</label>
                    <input type="text" name="subtitulo1" value="{{ $catalogo->subtitulo1 ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título 1</label>
                    <input type="text" name="titulo1" value="{{ $catalogo->titulo1 ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto</label>
                    <textarea name="texto" class="ckeditor w-full rounded" rows="6">{!! $catalogo->texto ?? '' !!}</textarea>
                </div>

                <hr class="my-6">

                {{-- CATÁLOGO COMPLETO --}}
                <h4 class="text-[16px] font-semibold text-gray-600 mb-3">Catálogo completo</h4>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="foto">
                        Portada (recomendado 234x333) / (2MB tamaño máximo)
                    </label>

                    @if (!empty($catalogo->foto))
                        <img :src="foto.foto || '{{ $catalogo->foto }}'" alt="Portada" class="mb-3 w-full max-w-[234px] object-cover">
                    @endif

                    <input type="file" name="foto" id="foto" accept="image/*" @change="subirFoto"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="archivo">
                        Archivo PDF (50MB tamaño máximo)
                    </label>

                    @if (!empty($catalogo->archivo))
                        <p class="mb-2 text-sm text-gray-600">
                            Archivo actual:
                            <a href="{{ $catalogo->archivo }}" target="_blank" class="text-blue-600 underline">ver PDF</a>
                            @if($catalogo->paginas) · {{ $catalogo->paginas }} páginas @endif
                            @if($catalogo->peso) · {{ $catalogo->pesoFormateado(1) }} @endif
                            @if($catalogo->fecha_archivo) · cargado el {{ $catalogo->fecha_archivo->format('d/m/Y') }} @endif
                        </p>
                    @endif

                    <input type="file" name="archivo" id="archivo" accept="application/pdf"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    <p class="mt-1 text-xs text-gray-500">Las páginas, el peso y la fecha se toman del archivo automáticamente al subirlo.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo del archivo</label>
                    <input type="text" name="subtitulo_archivo" value="{{ $catalogo->subtitulo_archivo ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título del archivo</label>
                    <input type="text" name="titulo_archivo" value="{{ $catalogo->titulo_archivo ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Descripción del archivo</label>
                    <textarea name="descripcion_archivo" class="ckeditor w-full rounded" rows="6">{!! $catalogo->descripcion_archivo ?? '' !!}</textarea>
                </div>

                <hr class="my-6">

                {{-- POR CATEGORÍA --}}
                <h4 class="text-[16px] font-semibold text-gray-600 mb-3">Por categoría</h4>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo 2 (texto chico sobre el título)</label>
                    <input type="text" name="subtitulo2" value="{{ $catalogo->subtitulo2 ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título 2</label>
                    <input type="text" name="titulo2" value="{{ $catalogo->titulo2 ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Categorías (nombre y PDF descargable)</label>

                    <div id="categorias-lista" class="flex flex-col gap-3">
                        @foreach(($catalogo->categorias ?? []) as $i => $categoria)
                            <div class="categoria-fila border border-gray-300 rounded p-3 flex flex-col gap-2">
                                <input type="hidden" name="categorias[{{ $i }}][archivo_actual]" value="{{ $categoria['archivo'] ?? '' }}">

                                <input type="text" name="categorias[{{ $i }}][nombre]" value="{{ $categoria['nombre'] ?? '' }}"
                                    placeholder="Nombre de la categoría"
                                    class="w-full border border-gray-300 rounded px-4 py-2">

                                @if(!empty($categoria['archivo']))
                                    <p class="text-sm text-gray-600">
                                        Archivo actual: <a href="{{ $categoria['archivo'] }}" target="_blank" class="text-blue-600 underline">ver PDF</a>
                                    </p>
                                @endif

                                <input type="file" name="categorias[{{ $i }}][archivo]" accept="application/pdf"
                                    class="w-full border border-gray-300 rounded px-4 py-2">

                                <button type="button" class="quitar-categoria self-start text-sm text-red-600 underline">Quitar categoría</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="agregar-categoria" class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-100">
                        + Agregar categoría
                    </button>
                </div>

                <button type="submit" class="border rounded p-4 bg-white">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('includes.ckeditor-init')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lista = document.getElementById('categorias-lista');
    let indice = {{ count($catalogo->categorias ?? []) }};

    document.getElementById('agregar-categoria').addEventListener('click', function () {
        const fila = document.createElement('div');
        fila.className = 'categoria-fila border border-gray-300 rounded p-3 flex flex-col gap-2';
        fila.innerHTML =
            '<input type="hidden" name="categorias[' + indice + '][archivo_actual]" value="">' +
            '<input type="text" name="categorias[' + indice + '][nombre]" placeholder="Nombre de la categoría" class="w-full border border-gray-300 rounded px-4 py-2">' +
            '<input type="file" name="categorias[' + indice + '][archivo]" accept="application/pdf" class="w-full border border-gray-300 rounded px-4 py-2">' +
            '<button type="button" class="quitar-categoria self-start text-sm text-red-600 underline">Quitar categoría</button>';
        lista.appendChild(fila);
        indice++;
    });

    lista.addEventListener('click', function (e) {
        const boton = e.target.closest('.quitar-categoria');
        if (boton) {
            boton.closest('.categoria-fila').remove();
        }
    });
});
</script>
@endpush
