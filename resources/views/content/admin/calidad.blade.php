@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div id="app">
    {{-- cabezal  --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Calidad</h3>
        <hr class="mx-6">
    </div>

    {{-- Editor --}}
    <div class="mx-20 pt-6">
        <div class="border rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar Calidad</h3>
            </div>
            <hr class="my-3">

            <form method="POST" action="{{ route('adm.calidad-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="calidad_id" value="{{ $calidad->id ?? '' }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo (texto chico sobre el título)</label>
                    <input type="text" name="subtitulo" value="{{ $calidad->subtitulo ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título</label>
                    <input type="text" name="titulo" value="{{ $calidad->titulo ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto (bajo el título)</label>
                    <input type="text" name="texto" value="{{ $calidad->texto ?? '' }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="foto">
                        Foto (recomendado 600x300) / (2MB tamaño máximo)
                    </label>

                    @if (!empty($calidad->foto))
                        <img :src="foto.foto || '{{ $calidad->foto }}'" alt="Foto" class="mb-3 w-full max-w-[600px] h-[300px] object-cover">
                    @endif

                    <input type="file" name="foto" id="foto" accept="image/*" @change="subirFoto"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Descripción</label>
                    <textarea name="descripcion" class="ckeditor w-full rounded" rows="8">{!! $calidad->descripcion ?? '' !!}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Las listas con viñetas se muestran con el ícono de check.</p>
                </div>

                <hr class="my-6">

                {{-- CERTIFICADOS --}}
                <h4 class="text-[16px] font-semibold text-gray-600 mb-1">Certificados</h4>
                <p class="text-sm text-gray-500 mb-3">
                    El orden es el de las filas. Si el archivo es una imagen se muestra en la web y se amplía al hacer clic;
                    si es otro formato (PDF, Word, Excel, ZIP) se muestra el ícono de descarga. Los cambios se guardan con "Actualizar".
                </p>

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead class="bg-gray-100">
                            <tr class="text-gray-700 text-sm uppercase">
                                <th class="px-4 py-3 text-center">Orden</th>
                                <th class="px-4 py-3 text-left">Foto (2MB máx.)</th>
                                <th class="px-4 py-3 text-left">Nombre</th>
                                <th class="px-4 py-3 text-left">Archivo (20MB máx.)</th>
                                <th class="px-4 py-3 text-center">Activo</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="certificados-lista" class="text-gray-600">
                            @foreach($certificados as $i => $certificado)
                                <tr class="certificado-fila border-t align-top">
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <input type="hidden" name="certificados[{{ $i }}][id]" value="{{ $certificado->id }}">
                                        <button type="button" class="subir-certificado px-1" title="Subir">&uarr;</button>
                                        <button type="button" class="bajar-certificado px-1" title="Bajar">&darr;</button>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(!empty($certificado->foto))
                                            <img src="{{ asset($certificado->foto) }}" alt="{{ $certificado->nombre }}"
                                                 class="mb-2 w-[60px] h-[60px] object-cover rounded border border-gray-300">
                                        @endif
                                        <input type="file" name="certificados[{{ $i }}][foto]" accept="image/*"
                                               class="w-full border border-gray-300 rounded px-2 py-1">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="certificados[{{ $i }}][nombre]" value="{{ $certificado->nombre }}"
                                               class="w-full border border-gray-300 rounded px-3 py-2">
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(!empty($certificado->archivo))
                                            <p class="mb-2 text-sm">
                                                <a href="{{ asset($certificado->archivo) }}" target="_blank" class="text-blue-600 underline">
                                                    {{ strtoupper($certificado->extension()) }}@if($certificado->pesoFormateado()) - {{ $certificado->pesoFormateado() }}@endif
                                                </a>
                                            </p>
                                        @endif
                                        <input type="file" name="certificados[{{ $i }}][archivo]"
                                               accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                                               class="w-full border border-gray-300 rounded px-2 py-1">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="hidden" name="certificados[{{ $i }}][active]" value="0">
                                        <input type="checkbox" name="certificados[{{ $i }}][active]" value="1" {{ $certificado->active ? 'checked' : '' }}>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="quitar-certificado text-red-600 font-bold hover:underline">Borrar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" id="agregar-certificado" class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-100">
                        + Agregar certificado
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
    const lista = document.getElementById('certificados-lista');
    let indice = {{ count($certificados) }};

    document.getElementById('agregar-certificado').addEventListener('click', function () {
        const fila = document.createElement('tr');
        fila.className = 'certificado-fila border-t align-top';
        fila.innerHTML =
            '<td class="px-4 py-3 text-center whitespace-nowrap">' +
                '<input type="hidden" name="certificados[' + indice + '][id]" value="">' +
                '<button type="button" class="subir-certificado px-1" title="Subir">&uarr;</button>' +
                '<button type="button" class="bajar-certificado px-1" title="Bajar">&darr;</button>' +
            '</td>' +
            '<td class="px-4 py-3"><input type="file" name="certificados[' + indice + '][foto]" accept="image/*" class="w-full border border-gray-300 rounded px-2 py-1"></td>' +
            '<td class="px-4 py-3"><input type="text" name="certificados[' + indice + '][nombre]" class="w-full border border-gray-300 rounded px-3 py-2"></td>' +
            '<td class="px-4 py-3"><input type="file" name="certificados[' + indice + '][archivo]" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip" class="w-full border border-gray-300 rounded px-2 py-1"></td>' +
            '<td class="px-4 py-3 text-center">' +
                '<input type="hidden" name="certificados[' + indice + '][active]" value="0">' +
                '<input type="checkbox" name="certificados[' + indice + '][active]" value="1" checked>' +
            '</td>' +
            '<td class="px-4 py-3 text-center"><button type="button" class="quitar-certificado text-red-600 font-bold hover:underline">Borrar</button></td>';
        lista.appendChild(fila);
        indice++;
    });

    lista.addEventListener('click', function (e) {
        const fila = e.target.closest('.certificado-fila');
        if (!fila) return;

        if (e.target.closest('.quitar-certificado')) {
            fila.remove();
        } else if (e.target.closest('.subir-certificado') && fila.previousElementSibling) {
            lista.insertBefore(fila, fila.previousElementSibling);
        } else if (e.target.closest('.bajar-certificado') && fila.nextElementSibling) {
            lista.insertBefore(fila.nextElementSibling, fila);
        }
    });
});
</script>
@endpush
