@php
    // Foto principal primero, luego la galería (sin duplicados)
    $allFotos = collect();
    if (!empty($producto->foto)) {
        $allFotos->push((object) ['foto' => $producto->foto]);
    }
    foreach ($producto->fotos->where('active', true) as $f) {
        if (empty($producto->foto) || $f->foto != $producto->foto) {
            $allFotos->push($f);
        }
    }

    $fichaTecnica    = collect($producto->ficha_tecnica ?? [])->filter(fn ($f) => !empty($f['nombre']) || !empty($f['valor']));
    $caracteristicas = collect($producto->caracteristicas ?? [])->filter(fn ($c) => !empty($c['foto']) || !empty($c['titulo']));
    $colores         = collect($producto->colores ?? [])->filter(fn ($c) => !empty($c['tipo']));
    $paleta          = \App\Models\Producto::COLORES;

    // Tabla de tamaños: cada "*" de una celda se muestra como check y se descarta el párrafo vacío final
    $check = '<img src="' . asset('assets/img/productos/check.svg') . '" alt="Sí" class="inline-block w-[24px] h-[24px]">';
    $tablaTamanos = $producto->tamaños
        ? preg_replace(
            ['/>(?:\s|&nbsp;)*\*(?:\s|&nbsp;)*</', '/(?:<p>(?:\s|&nbsp;)*<\/p>\s*)+$/'],
            ['>' . $check . '<', ''],
            $producto->tamaños
        )
        : null;

    $hayDescripcion     = $fichaTecnica->isNotEmpty() || $producto->aplicacion || $producto->certificado;
    $hayCaracteristicas = $caracteristicas->isNotEmpty() || $producto->tamaños || $colores->isNotEmpty();
@endphp

@extends('layouts.web')

@section('title', $producto->nombre_completo ?: $producto->nombre ?: 'Producto')

@section('content')

<style>
    /* Tabla de "tamaños" (HTML del editor) con el estilo del diseño */
    /* Cada <figure> del editor ocupa todo el ancho y comparten las mismas columnas (table-layout: fixed) */
    .tabla-html figure.table { display: block; width: 100% !important; margin: 0; }
    .tabla-html table { width: 100% !important; table-layout: fixed; border-collapse: collapse; font-size: 16px; color: #000; }
    .tabla-html td, .tabla-html th { height: 40px; padding: 0 8px 0 0; border-bottom: 1px solid #DEDFE0; text-align: left; vertical-align: middle; }
    .tabla-html td:first-child, .tabla-html th:first-child { width: 34%; }
    /* Todas las columnas menos la primera van centradas (título, valores y checks) */
    .tabla-html td:not(:first-child), .tabla-html th:not(:first-child) { text-align: center; }
    .tabla-html td img { display: block; margin: 0 auto; }
</style>

<div class="letra bg-[#F2F5F8] text-[#303030]">
    <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[120px]">

        {{-- breadcrumb --}}
        <p class="text-[12px] leading-[1.5]">
            <a href="{{ route('home') }}" class="font-bold">Inicio</a> &gt;
            <a href="{{ route('productos') }}" class="font-bold">Productos</a> &gt;
            {{ $producto->nombre }}
        </p>

        {{-- producto: galería + datos --}}
        <div class="mt-[72px] grid grid-cols-1 lg:grid-cols-2 gap-[24px]">

            {{-- galería --}}
            <div class="flex flex-col-reverse lg:flex-row gap-[24px]">
                @if($allFotos->count() > 1)
                <div class="flex flex-row lg:flex-col gap-[16px] shrink-0">
                    @foreach($allFotos as $idx => $foto)
                        @php $esVideo = \App\Models\ProductoFoto::urlEsVideo($foto->foto); @endphp
                        <button type="button"
                                class="thumb-wrapper relative w-[80px] h-[80px] bg-white overflow-hidden hover:scale-100 {{ $idx === 0 ? 'border-2 border-[#086AFF]' : 'border border-[#DEDFE0]' }}"
                                data-src="{{ asset($foto->foto) }}"
                                data-tipo="{{ $esVideo ? 'video' : 'imagen' }}">
                            @if($esVideo)
                                <video src="{{ asset($foto->foto) }}#t=0.5" preload="metadata" muted playsinline class="w-full h-full object-cover bg-black pointer-events-none"></video>
                                <span class="absolute inset-0 bg-black/10 flex items-center justify-center pointer-events-none">
                                    <img src="{{ asset('assets/img/productos/play.svg') }}" alt="Video" class="w-[24px] h-[24px]">
                                </span>
                            @else
                                <img src="{{ asset($foto->foto) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain">
                            @endif
                        </button>
                    @endforeach
                </div>
                @endif

                <div class="w-full lg:w-[496px] aspect-square bg-white border border-[#DEDFE0] flex items-center justify-center overflow-hidden">
                    @if($allFotos->count() > 0)
                        @php $primeroEsVideo = \App\Models\ProductoFoto::urlEsVideo($allFotos->first()->foto); @endphp
                        <img id="main-image" @if(!$primeroEsVideo) src="{{ asset($allFotos->first()->foto) }}" @endif alt="{{ $producto->nombre }}" class="w-full h-full object-contain {{ $primeroEsVideo ? 'hidden' : '' }}">
                        <video id="main-video" @if($primeroEsVideo) src="{{ asset($allFotos->first()->foto) }}" @endif controls playsinline preload="metadata" class="w-full h-full object-contain bg-black {{ $primeroEsVideo ? '' : 'hidden' }}"></video>
                    @endif
                </div>
            </div>

            {{-- datos --}}
            <div class="flex flex-col gap-[24px]">
                <div>
                    <h1 class="text-[26px] lg:text-[32px] leading-[1.2] font-bold text-[#003879]">
                        {{ $producto->nombre_completo ?: $producto->nombre }}
                    </h1>
                    @if($producto->descripcion)
                        <hr class="mt-[24px] border-[#DEDFE0]">
                        <div class="mt-[24px] text-[16px] leading-[1.5]">{!! $producto->descripcion !!}</div>
                    @endif
                </div>

                <div class="mt-auto flex flex-col sm:flex-row gap-[24px]">
                    @if(!empty($producto->archivo))
                        <a href="{{ asset($producto->archivo) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center w-full sm:w-[288px] bg-white border border-[#086AFF] p-[10px] text-[16px] leading-[1.5] text-[#086AFF]">
                            FICHA TÉCNICA
                        </a>
                    @endif
                    <a href="{{ route('contacto', ['producto' => $producto->id]) }}"
                       class="flex items-center justify-center w-full sm:w-[288px] bg-[#086AFF] p-[10px] text-[16px] leading-[1.5] text-white">
                        CONSULTAR
                    </a>
                </div>
            </div>
        </div>

        {{-- descripción --}}
        @if($hayDescripcion)
        <div class="mt-[80px] grid grid-cols-1 lg:grid-cols-2 gap-x-[24px] gap-y-[24px]">
            <div>
                <h2 class="text-[26px] lg:text-[32px] leading-[1.2] font-bold text-[#003879]">Descripción</h2>
                @if($fichaTecnica->isNotEmpty())
                <div class="mt-[24px] border-t border-[#DEDFE0]">
                    @foreach($fichaTecnica as $dato)
                        <div class="py-[6px] border-b border-[#DEDFE0] text-black">
                            <p class="text-[16px] leading-[1.5]">{{ $dato['nombre'] }}</p>
                            <p class="text-[16px] leading-[1.5] font-bold">{!! nl2br(e($dato['valor'])) !!}</p>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="lg:pt-[62px]">
                @if($producto->aplicacion)
                <div class="border-t border-[#DEDFE0] py-[6px] text-black">
                    <p class="text-[16px] leading-[1.5]">Aplicación</p>
                    <div class="text-[16px] leading-[1.5] font-bold">{!! $producto->aplicacion !!}</div>
                </div>
                @endif

                @if($producto->certificado)
                <div class="border-t border-[#DEDFE0] pt-[12px] text-[14px] leading-[1.5] text-black">
                    {!! $producto->certificado !!}
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- características --}}
        @if($hayCaracteristicas)
        <div class="mt-[80px] grid grid-cols-1 lg:grid-cols-2 gap-x-[24px] gap-y-[24px]">
            <div>
                @if($caracteristicas->isNotEmpty())
                    <h2 class="text-[26px] lg:text-[32px] leading-[1.2] font-bold text-[#003879]">Características</h2>

                    {{-- 5 por fila; el ícono ya trae su propio marco, por eso no se le agrega borde --}}
                    <div class="mt-[24px] grid grid-cols-3 sm:grid-cols-5 gap-x-[16px] xl:gap-x-[31px] gap-y-[20px]">
                        @foreach($caracteristicas as $caracteristica)
                            <div class="flex flex-col items-center">
                                @if(!empty($caracteristica['foto']))
                                    <div class="w-full max-w-[95px] aspect-square bg-[#086AFF]"
                                         style="-webkit-mask: url('{{ asset($caracteristica['foto']) }}') center/contain no-repeat; mask: url('{{ asset($caracteristica['foto']) }}') center/contain no-repeat;"></div>
                                @else
                                    <div class="w-full max-w-[95px] aspect-square border-2 border-[#086AFF] rounded-[4px]"></div>
                                @endif
                                <p class="mt-[8px] w-full max-w-[100px] text-[12px] leading-[1.5] text-center text-black break-words">{!! nl2br(e($caracteristica['titulo'] ?? '')) !!}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($colores->isNotEmpty())
                    <h2 class="mt-[24px] text-[26px] lg:text-[32px] leading-[1.2] font-bold text-[#003879]">Colores de aislación</h2>

                    <div class="mt-[24px] border-t border-[#DEDFE0]">
                        @foreach($colores as $grupo)
                            <div class="min-h-[66px] py-[18px] border-b border-[#DEDFE0] flex flex-nowrap items-center gap-[24px]">
                                <p class="basis-[261px] shrink min-w-0 text-[20px] leading-[1.5] text-black">{{ $grupo['tipo'] }}</p>
                                <div class="flex flex-nowrap shrink-0 gap-[10px] sm:gap-[16px]">
                                    @foreach(($grupo['colores'] ?? []) as $clave)
                                        @if(isset($paleta[$clave]))
                                            <span title="{{ $paleta[$clave]['nombre'] }}"
                                                  class="inline-block w-[30px] h-[30px] rounded-full border border-[#DEDFE0]"
                                                  style="background: {{ $paleta[$clave]['estilo'] }}"></span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($tablaTamanos)
            <div class="w-full overflow-x-auto {{ $caracteristicas->isNotEmpty() ? 'lg:pt-[54px]' : '' }}">
                <div class="tabla-html w-full">{!! $tablaTamanos !!}</div>
            </div>
            @endif
        </div>
        @endif

    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const main = document.getElementById('main-image');
    const mainVideo = document.getElementById('main-video');
    const thumbs = Array.from(document.querySelectorAll('.thumb-wrapper'));
    if (!main || !mainVideo || thumbs.length === 0) return;

    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            if (this.dataset.tipo === 'video') {
                main.classList.add('hidden');
                mainVideo.classList.remove('hidden');
                mainVideo.setAttribute('src', this.dataset.src);
            } else {
                mainVideo.pause();
                mainVideo.classList.add('hidden');
                main.classList.remove('hidden');
                main.setAttribute('src', this.dataset.src);
            }

            thumbs.forEach(function (t) {
                t.classList.remove('border-2', 'border-[#086AFF]');
                t.classList.add('border', 'border-[#DEDFE0]');
            });
            this.classList.remove('border', 'border-[#DEDFE0]');
            this.classList.add('border-2', 'border-[#086AFF]');
        });
    });
});
</script>
@endsection
