@extends('layouts.master')

@section('title', 'Productos')

@section('content')

{{-- Cabezal --}}
<div class="flex items-center justify-between px-4 pt-4 pb-2">
    <h3 class="text-[20px] font-bold text-gray-500">Productos</h3>
</div>
<hr class="mx-6 mb-2">

{{-- Listado --}}
<div class="p-4">
    <div class="overflow-x-auto rounded-2xl shadow">
        <div class="flex items-center justify-between px-6 py-3 border rounded gap-4">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('adm.producto') }}" class="flex items-center gap-2 flex-1 flex-wrap">
                <input name="q" value="{{ request('q') }}" placeholder="Buscar por nombre"
                    class="w-full max-w-xs border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">

                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded">Buscar</button>
            </form>

            <a href="{{ route('adm.producto-creador') }}"
            class="hover:text-gray-400 hover:scale-105 transition-transform duration-300">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <table class="min-w-full border border-gray-200 bg-white">
            <thead class="bg-gray-100">
                <tr class="text-gray-700 text-sm uppercase">
                    <th class="px-4 py-3 text-left">Orden</th>
                    <th class="px-4 py-3 text-center">Foto</th>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Nombre completo</th>
                    <th class="px-4 py-3 text-center">Activo</th>
                    <th class="px-4 py-3 text-center">Destacado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="producto-tabla" class="text-gray-600">
                @foreach($productos as $item)
                    <tr class="border-t hover:bg-gray-50 @if(!$productos->hasPages()) cursor-move @endif" data-id="{{ $item->id }}">
                        <td class="px-4 py-3">{{ $item->orden }}</td>
                        <td class="px-4 py-3 text-center">
                            @if (!empty($item->foto))
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->nombre }}" class="w-[60px] h-[60px] object-cover mx-auto rounded">
                            @endif
                        </td>
                        <td class="px-4 py-3 w-64 max-w-64 truncate">{{ $item->nombre }}</td>
                        <td class="px-4 py-3 max-w-md truncate">{{ $item->nombre_completo }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($item->active)
                                <a href="{{ route('adm.producto-switch', $item->id) }}" class="badge bg-green-400 text-white px-4 py-2 rounded">Activo</a>
                            @else
                                <a href="{{ route('adm.producto-switch', $item->id) }}" class="badge bg-red-400 text-white px-4 py-2 rounded">Inactivo</a>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($item->destacado)
                                <a href="{{ route('adm.producto-destacado', $item->id) }}" class="badge bg-green-400 text-white px-4 py-2 rounded">Destacado</a>
                            @else
                                <a href="{{ route('adm.producto-destacado', $item->id) }}" class="badge bg-red-400 text-white px-4 py-2 rounded">No destacado</a>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center space-x-2 font-bold">
                            <a href="{{ route('adm.producto-editor', $item->id) }}" class="text-blue-600 hover:underline">Editar</a>
                            <span>|</span>
                            <a href="{{ route('adm.producto-destroy', $item->id) }}" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar producto?')">Borrar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginado --}}
        @if($productos->hasPages())
            <div class="flex items-center justify-between px-6 py-3 border-t bg-white text-sm text-gray-600">
                <span>
                    Mostrando {{ $productos->firstItem() }}–{{ $productos->lastItem() }} de {{ $productos->total() }} productos
                </span>
                <div class="flex gap-1">
                    {{-- Anterior --}}
                    @if($productos->onFirstPage())
                        <span class="px-3 py-1 rounded border border-gray-200 text-gray-300">‹</span>
                    @else
                        <a href="{{ $productos->previousPageUrl() }}" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100 transition">‹</a>
                    @endif

                    {{-- Páginas --}}
                    @foreach($productos->getUrlRange(max(1, $productos->currentPage()-2), min($productos->lastPage(), $productos->currentPage()+2)) as $page => $url)
                        @if($page == $productos->currentPage())
                            <span class="px-3 py-1 rounded bg-blue-600 text-white border border-blue-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Siguiente --}}
                    @if($productos->hasMorePages())
                        <a href="{{ $productos->nextPageUrl() }}" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-100 transition">›</a>
                    @else
                        <span class="px-3 py-1 rounded border border-gray-200 text-gray-300">›</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(!$productos->hasPages())
        activarOrdenDragDrop('#producto-tabla', '{{ route('adm.producto-reordenar') }}');
    @else
        // Deshabilitar cursor de arrastre visualmente
        document.querySelectorAll('#producto-tabla tr').forEach(function(tr) {
            tr.classList.remove('cursor-move');
            tr.style.cursor = 'default';
        });
    @endif
});
</script>

@endsection
