@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Editar Producto</h3>
        <hr class="mx-6">
    </div>

    {{-- Formulario --}}
    <div class="mx-20 pt-6" id="app">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar producto</h3>
            </div>
            <hr class="my-3">

            <form method="POST"
                  action="{{ route('adm.producto-update') }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="producto_id" value="{{ $producto->id ?? '' }}">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('content.admin.partials.producto-campos')

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">
                    Actualizar
                </button>
            </form>
        </div>
    </div>

    <hr class="my-10">

    {{-- GALERÍA DE FOTOS --}}
    <div class="mx-20">
        <form id="form-galeria" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="productos_id" value="{{ $producto->id }}">

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Agregar fotos o videos para la galería</label>
                <input type="file" name="fotos[]" id="fotos" multiple accept="image/*,video/mp4,video/webm" class="w-full border border-gray-300 rounded px-4 py-2" />
                <p class="text-xs text-gray-500 mt-1">Fotos: 496x496, 2MB máximo. Videos: MP4 o WebM, 50MB máximo.</p>
                <p class="text-sm text-gray-500 mt-1" id="file-count">No hay archivos seleccionados</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Orden inicial</label>
                <input type="number" name="orden" value="0" class="w-full border border-gray-300 rounded px-4 py-2">
                <p class="text-sm text-gray-500 mt-1">Las fotos se agregarán con orden consecutivo</p>
            </div>

            <button type="submit" id="btn-subir-fotos" class="border rounded p-4 bg-white hover:bg-gray-50">Agregar Fotos</button>
            <span id="loading-galeria" class="ml-3 text-gray-600 hidden">Subiendo...</span>
        </form>

        <div class="p-4 mt-6">
            <div class="overflow-x-auto rounded-2xl shadow">
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700 text-sm uppercase">
                            <th class="px-4 py-3 text-left">Orden</th>
                            <th class="px-4 py-3 text-center">Foto</th>
                            <th class="px-4 py-3 text-center">Activo</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="galeria-fotos" class="text-gray-600">
                        @foreach($producto->fotos as $foto)
                            <tr class="border-t hover:bg-gray-50 cursor-move" data-id="{{ $foto->id }}">
                                <td class="px-4 py-3">{{ $foto->orden }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($foto->es_video)
                                        <div class="relative w-[94px] h-[94px] mx-auto">
                                            <video src="{{ asset($foto->foto) }}#t=0.5" preload="metadata" muted playsinline class="w-full h-full object-cover rounded bg-black"></video>
                                            <span class="absolute bottom-1 left-1 bg-black/70 text-white text-[10px] px-1 rounded">VIDEO</span>
                                        </div>
                                    @else
                                        <img src="{{ asset($foto->foto) }}" alt="" class="w-[94px] h-[94px] object-cover mx-auto rounded">
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($foto->active)
                                        <a href="{{ route('adm.producto-fotos-switch', $foto->id) }}" class="badge bg-green-400 text-white px-4 py-2 rounded">Activo</a>
                                    @else
                                        <a href="{{ route('adm.producto-fotos-switch', $foto->id) }}" class="badge bg-red-400 text-white px-4 py-2 rounded">Inactivo</a>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('adm.producto-fotos-destroy', $foto->id) }}" class="text-red-600 hover:underline btn-borrar-foto">Borrar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@include('includes.ckeditor-init')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formGaleria = document.getElementById('form-galeria');
    if (!formGaleria) return;

    const btnSubmit = document.getElementById('btn-subir-fotos');
    const loading = document.getElementById('loading-galeria');
    const fileCount = document.getElementById('file-count');

    document.getElementById('fotos')?.addEventListener('change', function () {
        const count = this.files.length;
        fileCount.textContent = count === 0 ? 'No hay archivos seleccionados' : (count === 1 ? '1 archivo seleccionado' : count + ' archivos seleccionados');
    });

    formGaleria.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        btnSubmit.disabled = true;
        loading.classList.remove('hidden');

        fetch('{{ route('adm.producto-fotos-store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json().catch(() => ({})).then(data => ({ ok: res.ok, data: data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                // recargar la página para actualizar la galería
                location.reload();
            } else {
                // 413/upload_max_filesize de PHP llegan sin JSON: se muestra un mensaje genérico
                alert(data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Error al subir los archivos. Revisá el tamaño (fotos 2MB, videos 50MB).'));
            }
        })
        .catch(() => alert('Error al subir los archivos'))
        .finally(() => { btnSubmit.disabled = false; loading.classList.add('hidden'); });
    });

    // borrar foto
    document.querySelectorAll('.btn-borrar-foto').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!confirm('¿Deseas eliminar esta foto?')) return;
            const url = this.getAttribute('href');
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                })
                .catch(() => alert('Error al eliminar la foto'));
        });
    });

    // activar reorden
    activarOrdenDragDrop('#galeria-fotos', '{{ route('adm.producto-fotos-reordenar') }}');
});
</script>
@endpush
