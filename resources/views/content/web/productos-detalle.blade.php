@php
    // Variables recibidas: $producto, $marcas, $productos_ban, $related
    $marcas = $marcas ?? App\Models\Marca::where('active', true)->orderBy('orden')->get();
    // Preparar colección de fotos: foto general primero, luego galería (sin duplicados)
    $allFotos = collect();
    if(!empty($producto->foto)) {
        $allFotos->push((object)['foto' => $producto->foto]);
    }
    if(isset($producto->fotos) && $producto->fotos->count() > 0) {
        foreach($producto->fotos as $f) {
            if(empty($producto->foto) || ($f->foto != $producto->foto)) {
                $allFotos->push($f);
            }
        }
    }
@endphp

@extends('layouts.web')

@section('title', $producto->nombre ?? 'Producto')

@section('content')

{{-- Breadcrumb minimalista --}}
<div class="w-full bg-white  text-[12px] pt-4">
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px]">
        <span class="font-medium">Inicio</span> 
        <span class="font-medium mx-1">&gt;</span> 
        <span class="font-medium">Productos</span> 
        <span class="font-medium mx-1">&gt;</span> 
        <span class="font-normal">{{ $producto->nombre }}</span>
    </div>
</div>

<div class="h-auto font-sans mx-auto px-[5%] md:px-[71px] max-w-[1366px] pt-6 pb-[80px]">
    <div class="w-full flex flex-col lg:flex-row gap-[50px] items-start">

        {{-- Galería y foto principal (Invertido el orden para que las miniaturas queden a la izquierda en desktop) --}}
        <div class="w-full lg:w-[60%] flex flex-col-reverse lg:flex-row gap-4">
            
            {{-- Miniaturas laterales --}}
            <div class="w-full lg:w-[15%] flex flex-row lg:flex-col gap-3">
                @if($allFotos->count() > 0)
                    @foreach($allFotos as $idx => $foto)
                        <div class="w-[70px] h-[70px] border border-gray-200 rounded-[8px] p-1 flex items-center justify-center cursor-pointer hover:border-gray-400 transition thumb-wrapper {{ $idx === 0 ? ' ' : '' }}" data-src="{{ asset($foto->foto) }}">
                            <img src="{{ asset($foto->foto) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain thumb-img" data-src="{{ asset($foto->foto) }}">
                        </div>
                    @endforeach
                @else
                    <div class="w-[70px] h-[70px] border border-gray-200 rounded-[8px] p-1 flex items-center justify-center cursor-pointer">
                        <div class="text-gray-300 text-xs">Sin imagen</div>
                    </div>
                @endif
            </div>

            {{-- Contenedor de la Imagen Principal --}}
            <div class="w-full lg:w-[85%] bg-white border border-gray-200 rounded-[12px] p-4 flex items-center justify-center">
                <div class="w-full lg:w-[510px] lg:h-[400px] flex items-center justify-center bg-white">
                    @if($allFotos->count() > 0)
                        <img id="main-image" src="{{ asset($allFotos->first()->foto) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">Sin imagen</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Información del producto (Derecha) --}}
        <div class="w-full lg:w-[40%] flex flex-col justify-between h-full min-h-[450px]">
            <div>
                {{-- Código destacado en rojo oscuro --}}
                <div class="text-[18px] text-[#A30D11] font-bold mb-3">{{ $producto->codigo }}</div>
                
                {{-- Título principal --}}
                <h1 class="text-[24px] font-bold text-[#1A1A1A] leading-tight mb-6">{{ $producto->nombre }}</h1>

                {{-- Tabla limpia de especificaciones --}}
                <div class="bg-white border-t border-b border-gray-200 overflow-hidden mb-8">
                    <table class="w-full text-[14px]">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-500 font-normal w-1/3">Marca</td>
                                <td class="py-3 text-[#1A1A1A] text-right font-normal">{{ optional($producto->marca)->nombre ?? optional($producto->marca)->texto ?? 'Mercedes Benz' }}</td>
                            </tr>
                            @if(!empty($producto->largo))
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-500 font-normal">Largo</td>
                                <td class="py-3 text-[#1A1A1A] text-right font-normal">{{ $producto->largo }}mm</td>
                            </tr>
                            @endif
                            @if(!empty($producto->alambre) || !empty($producto->diametro))
                            <tr>
                                <td class="py-3 text-gray-500 font-normal">ØAlambre</td>
                                <td class="py-3 text-[#1A1A1A] text-right font-normal">{{ $producto->alambre ?? $producto->diametro ?? '' }}mm</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Botones de Acción inferiores --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-auto">
                @if(!empty($producto->archivo))
                    <a href="{{ asset($producto->archivo) }}" target="_blank" class="w-full sm:w-1/2 text-center py-3 border border-[#E1251B] text-[#E1251B] font-semibold rounded-[8px] hover:bg-[#E1251B] hover:text-white transition text-[15px]">Ver ficha técnica</a>
                @endif

                <a href="{{ route('contacto') }}" class="w-full sm:w-1/2 text-center py-3 bg-[#E1251B] text-white font-semibold rounded-[8px] hover:bg-[#C81E14] transition text-[15px]">Solicitar presupuesto</a>
            </div>
        </div>

    </div>

    {{-- Sección de Productos relacionados --}}
    <div class="mt-20">
        <h2 class="text-[22px] font-bold text-[#1A1A1A] mb-6">Productos relacionados</h2>

        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($related as $r)
                <a href="{{ route('productos.detalle', ['id' => $r->id]) }}" class="border border-gray-200 rounded-[12px] overflow-hidden bg-white flex flex-col justify-between transition-all duration-300 hover:shadow-lg p-5 group">
                    {{-- Contenedor imagen fija --}}
                    <div class="w-full h-[180px] flex items-center justify-center p-2 bg-white">
                        @if(!empty($r->foto))
                            <img src="{{ asset($r->foto) }}" alt="{{ $r->nombre }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">Sin imagen</div>
                        @endif
                    </div>
                    {{-- Datos --}}
                    <div class="mt-4 pt-4 border-t border-gray-50/50">
                        <div class="text-[14px] text-[#A30D11] font-bold mb-1">{{ $r->codigo }}</div>
                        <div class="text-[14px] font-medium text-[#1A1A1A] line-clamp-2 min-h-[40px] leading-snug">{{ $r->nombre }}</div>
                    </div>
                </a>
            @empty
                <div class="text-gray-500 col-span-3 py-4">No hay productos relacionados.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const main = document.getElementById('main-image');
    if (!main) return;
    const thumbWrappers = Array.from(document.querySelectorAll('.thumb-wrapper'));
    if (thumbWrappers.length === 0) return;

    // add a small CSS rule for selected thumb
    const styleId = 'thumb-selected-style';
    if (!document.getElementById(styleId)) {
        const style = document.createElement('style');
        style.id = styleId;
        style.innerHTML = '.selected-thumb{ box-shadow: 0 0 0 3px rgba(225,37,27,0.12); border-color: #E1251B !important; }';
        document.head.appendChild(style);
    }

    // initialize selected on first thumb
    thumbWrappers.forEach((t, i) => {
        if (i === 0) t.classList.add('selected-thumb');
        t.addEventListener('click', function (e) {
            e.preventDefault();
            const img = this.querySelector('img.thumb-img');
            const src = this.getAttribute('data-src') || img?.getAttribute('data-src') || img?.getAttribute('src');
            if (!src) return;
            // change main image
            main.setAttribute('src', src);
            // update selected class
            thumbWrappers.forEach(x => x.classList.remove('selected-thumb'));
            this.classList.add('selected-thumb');
        });
    });
});
</script>
@endsection
