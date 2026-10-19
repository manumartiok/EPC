@php
    $home_banners = App\Models\HomeBanner::where('active', true)->orderBy('orden')->get();
    $home = App\Models\Home::first() ?? new App\Models\Home();
    $catalogo = App\Models\Catalogo::first();

    $novedades = App\Models\Novedade::where('active', true)
        ->where('destacado', true)
        ->orderBy('orden')
        ->get();

    $productos = App\Models\Producto::where('active', true)
        ->where('destacado', true)
        ->orderBy('orden')
        ->get();
@endphp

@extends('layouts.web', ['navbarClass' => 'navbar-home'])

@section('title', 'Inicio')

@section('content')
<style>
    .navbar-home {
        background-color: transparent !important;
        position: absolute !important;
        color: white !important;
    }
</style>
<div class="letra">
    {{-- CARRUSEL BANNER --}}
    <div id="bannerCarousel" class="relative w-full h-[768px] overflow-hidden bg-[#003879] z-10">

        @foreach($home_banners as $index => $banner)
            <div class="carousel-slide absolute inset-0 transition-opacity duration-500
                {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">

                {{-- VIDEO O IMAGEN --}}
                @if (($banner->tipo_medio ?? 'imagen') === 'video')
                    <video class="absolute top-0 left-0 w-full h-full object-cover" autoplay muted loop playsinline>
                        <source src="{{ asset($banner->foto) }}" type="video/mp4">
                        Tu navegador no soporta video HTML5.
                    </video>
                @else
                    <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center"
                        style="background-image: url('{{ asset($banner->foto) }}')">
                    </div>
                @endif

                <div class="absolute inset-0 bg-black/40"></div>

                {{-- CONTENIDO --}}
                <div class="relative h-full max-w-[1366px] mx-auto px-[5%] lg:px-[71px] flex flex-col items-start justify-center text-white z-20 pt-[90px]">
                    @if($banner->texto_chico)
                        <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $banner->texto_chico }}</p>
                    @endif
                    <h1 class="text-[36px] lg:text-[48px] leading-[1.2] font-bold max-w-[700px]">{{ $banner->titulo }}</h1>
                    @if($banner->texto)
                        <div class="mt-[24px] text-[16px] leading-[1.5] max-w-[600px]">{!! $banner->texto !!}</div>
                    @endif

                    <div class="mt-[32px] flex flex-wrap gap-[25px]">
                        <a href="{{ route('productos') }}" class="flex items-center justify-center w-[184px] p-[10px] bg-[#086AFF] text-white text-[16px] leading-[1.5] uppercase">Ver productos</a>
                        <a href="{{ route('catalogo') }}" class="flex items-center justify-center p-[10px] bg-white border border-[#086AFF] text-[#086AFF] text-[16px] leading-[1.5] uppercase">Descargar catálogo</a>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- PUNTOS DEL CARRUSEL --}}
        @if ($home_banners->count() > 1)
            <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] absolute bottom-[40px] left-0 right-0 flex justify-start gap-[8px] z-30">
                @foreach ($home_banners as $index => $banner)
                    <span class="carousel-dot h-[5px] w-[36px] rounded-[2px] cursor-pointer transition-all duration-300
                         {{ $index === 0 ? 'bg-[#A60000]' : 'bg-[#D9D9D9]' }}"
                        data-index="{{ $index }}">
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- NOSOTROS --}}
    <section class="bg-[#F2F5F8]">
        <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] py-[80px] flex flex-col lg:flex-row gap-[40px] lg:gap-[24px] lg:items-start">
            <div class="w-full lg:w-[600px] lg:shrink-0">
                @if($home->texto_chico1)
                    <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $home->texto_chico1 }}</p>
                @endif
                <h2 class="text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">{{ $home->titulo1 }}</h2>
                <div class="mt-[24px] text-[16px] leading-[1.5] text-[#303030] [&_p]:mb-[24px]">{!! $home->descripcion !!}</div>
            </div>
            @if($home->foto1)
                <div class="w-full lg:w-[600px] h-[300px] lg:h-[400px] lg:ml-auto overflow-hidden">
                    <img src="{{ asset($home->foto1) }}" alt="{{ $home->titulo1 }}" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </section>

    {{-- PRODUCTOS DESTACADOS --}}
    <section class="bg-[#DEE7EE]">
        <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] py-[60px]">
            <div class="flex items-end justify-between gap-[16px] mb-[40px]">
                <div>
                    @if($home->texto_chico2)
                        <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $home->texto_chico2 }}</p>
                    @endif
                    <h2 class="text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">{{ $home->titulo2 }}</h2>
                </div>
                <a href="{{ route('productos') }}" class="shrink-0 flex items-center justify-center p-[10px] bg-white border border-[#086AFF] text-[#086AFF] text-[16px] leading-[1.5] uppercase">Ver todos</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px]">
                @foreach($productos as $producto)
                    <a href="{{ route('productos.detalle', ['id' => $producto->id]) }}"
                       class="bg-white border border-[#DEDFE0] flex flex-col">
                        <div class="w-full h-[230px] overflow-hidden flex items-center justify-center bg-white">
                            @if(!empty($producto->foto))
                                <img src="{{ asset($producto->foto) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain">
                            @endif
                        </div>

                        <div class="mx-[24px] border-t border-[#DEDFE0] flex items-center justify-between gap-[12px] h-[60px]">
                            <h3 class="text-[16px] leading-[1.2] font-bold text-[#003879]">{{ $producto->nombre }}</h3>
                            <img src="{{ asset('assets/img/productos/arrow-right.svg') }}" alt="" class="w-[16px] h-[16px] shrink-0">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CATÁLOGO --}}
    <section class="bg-[#003879] text-white">
        <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] py-[70px] flex flex-col lg:flex-row lg:items-center justify-between gap-[32px]">
            <div>
                @if($home->texto_chico3)
                    <p class="text-[16px] leading-[1.5] uppercase">{{ $home->texto_chico3 }}</p>
                @endif
                <h2 class="text-[32px] lg:text-[40px] leading-[1.2] font-bold max-w-[624px]">{{ $home->titulo3 }}</h2>
                @if($home->subtext3)
                    <p class="mt-[24px] text-[16px] leading-[1.5] max-w-[495px]">{{ $home->subtext3 }}</p>
                @endif
            </div>
            @if($catalogo && $catalogo->archivo)
                <a href="{{ $catalogo->archivo }}" target="_blank" rel="noopener" download
                   class="flex items-center justify-center w-full lg:w-[288px] p-[10px] bg-[#086AFF] text-white text-[16px] leading-[1.5] uppercase">Descargar catálogo</a>
            @else
                <a href="{{ route('catalogo') }}"
                   class="flex items-center justify-center w-full lg:w-[288px] p-[10px] bg-[#086AFF] text-white text-[16px] leading-[1.5] uppercase">Descargar catálogo</a>
            @endif
        </div>
    </section>

    {{-- NOVEDADES --}}
    <section class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] py-[60px]">
        <div class="flex items-end justify-between gap-[16px] mb-[40px]">
            <div>
                @if($home->texto_chico4)
                    <p class="text-[16px] leading-[1.5] text-[#086AFF] uppercase">{{ $home->texto_chico4 }}</p>
                @endif
                <h2 class="text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">{{ $home->titulo4 }}</h2>
            </div>
            <a href="{{ route('novedades') }}" class="shrink-0 flex items-center justify-center p-[10px] bg-white border border-[#086AFF] text-[#086AFF] text-[16px] leading-[1.5] uppercase">Ver todas</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">
            @foreach($novedades as $item)
                <a href="{{ route('novedades-detalle', ['id' => $item->id]) }}" class="group flex flex-col bg-white border border-[#ECEBEB] rounded-[4px] overflow-hidden">
                    <div class="h-[294px] w-full overflow-hidden">
                        <img src="{{ asset($item->foto) }}" alt="{{ $item->titulo }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col flex-1 p-[24px]">
                        @if($item->texto_chico)
                            <span class="text-[12px] leading-[1.5] font-bold text-[#00B1F6] uppercase">{{ $item->texto_chico }}</span>
                        @endif
                        <h3 class="mt-[8px] text-[24px] leading-[1.2] font-bold text-[#17181B]">{{ $item->titulo }}</h3>
                        <div class="mt-[16px] text-[16px] leading-[1.5] text-[#17181B] line-clamp-2">{!! strip_tags($item->texto) !!}</div>
                        <span class="mt-auto pt-[24px] text-[16px] leading-[1.5] font-bold text-[#17181B] uppercase group-hover:text-[#086AFF] transition-colors duration-200">Leer más</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- CERTIFICACIONES --}}
    @if($home->texto5)
        <section class="bg-[#F2F5F8]">
            <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] py-[60px] flex flex-col lg:flex-row lg:items-center justify-between gap-[32px]">
                <p class="text-[24px] leading-[1.5] text-[#303030] max-w-[533px]">{{ $home->texto5 }}</p>
                <div class="flex items-center gap-[22px]">
                    <img src="{{ asset('assets/img/1.png') }}" alt="" class="w-[87px] h-[87px] object-contain">
                    <img src="{{ asset('assets/img/2.png') }}" alt="" class="w-[87px] h-[96px] object-contain">
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // CÓDIGO PARA EL CARRUSEL DE BANNERS
    // =============================================
    const carousel = document.getElementById('bannerCarousel');
    const slides = carousel?.querySelectorAll('.carousel-slide');
    const dots = carousel?.querySelectorAll('.carousel-dot');
    
    if (slides && slides.length > 0) {
        let currentSlide = 0;
        let slideInterval;
        const slideDuration = 5000; // 5 segundos entre slides

        // Función para mostrar un slide específico
        function showSlide(index) {
            // Ocultar todos los slides
            slides.forEach(slide => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
                slide.style.zIndex = '0';
            });
            
            // Remover clase activa de todos los dots
            if (dots) {
                dots.forEach(dot => {
                    dot.classList.remove('bg-[#A60000]');
                    dot.classList.add('bg-[#D9D9D9]');
                });
            }
            
            // Mostrar slide actual
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
            slides[index].style.zIndex = '10';
            
            // Activar dot actual
            if (dots && dots[index]) {
                dots[index].classList.remove('bg-[#D9D9D9]');
                dots[index].classList.add('bg-[#A60000]');
            }
            
            currentSlide = index;
        }

        // Función para siguiente slide
        function nextSlide() {
            let nextIndex = (currentSlide + 1) % slides.length;
            showSlide(nextIndex);
        }

        // Función para slide anterior
        function prevSlide() {
            let prevIndex = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prevIndex);
        }

        // Iniciar autoplay
        function startAutoplay() {
            if (slides.length > 1) {
                slideInterval = setInterval(nextSlide, slideDuration);
            }
        }

        // Detener autoplay
        function stopAutoplay() {
            clearInterval(slideInterval);
        }

        // Event listeners para los dots
        if (dots) {
            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const slideIndex = parseInt(this.getAttribute('data-index'));
                    showSlide(slideIndex);
                    stopAutoplay();
                    startAutoplay(); // Reiniciar autoplay después de click manual
                });
            });
        }

        // Pausar autoplay al hacer hover sobre el carrusel
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);

        // Navegación con teclado (opcional)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopAutoplay();
                startAutoplay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopAutoplay();
                startAutoplay();
            }
        });

        // Inicializar carrusel
        startAutoplay();
        // Asegurarse de que el primer slide esté visible
        showSlide(0);
    }
});
</script>