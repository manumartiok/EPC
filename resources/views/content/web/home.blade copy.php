@php
    $home_banners = App\Models\HomeBanner::where('active', true)->orderBy('orden')->get();
    $home = App\Models\Home::first();

    $novedades = App\Models\Novedade::where('active', true)
        ->where('destacado', true)
        ->orderBy('orden')
        ->get(); 

    $marcas = App\Models\Marca::where('active', true)
        ->where('destacado', true)
        ->orderBy('orden')
        ->get(); 

    $marcasfiltro = App\Models\Marca::where('active', true)
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
    <div id="bannerCarousel" class="relative w-full h-[776px] overflow-hidden z-10">

        @foreach($home_banners as $index => $banner)
            <div class="carousel-slide absolute inset-0 transition-opacity duration-500 
                {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                
                {{-- VIDEO O IMAGEN --}}
                @if (($banner->tipo_medio ?? 'imagen') === 'video')
                    <video class="absolute top-0 left-0 w-full h-full object-cover" autoplay muted loop playsinline>
                        <source src="{{ asset($banner->video ?? $banner->foto) }}" type="video/mp4">
                        Tu navegador no soporta video HTML5.
                    </video>
                @else
                    <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center"
                        style="background-image: url('{{ asset($banner->foto) }}')">
                    </div>
                @endif

                <div class="absolute inset-0 bg-black/40 z-5"></div>

                {{-- CONTENIDO (MISMO DISEÑO QUE TENÍAS) --}}
                <div class="relative h-full max-w-[1366px] mx-auto px-[5%] md:px-[71px] flex flex-col items-start text-white z-20">
                    <h3 class="text-[35px] md:text-[46px] font-bold  mt-[314px] max-w-[850px]">
                        {{ $banner->titulo }}
                    </h3>
                    <a href="{{route('productos')}}" class="mt-[73px] flex justify-center items-center bg-[#DE2222] text-white text-[14px] w-[154px] h-[46px] rounded-[14px] ">Ver productos</a>
                </div>
            </div>
        @endforeach

        {{-- BARRAS/PUNTOS DEL CARRUSEL --}}
        @if ($home_banners->count() > 1)
            <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] absolute bottom-[140px] left-0 right-0 flex justify-start gap-[8px] z-30">
                @foreach ($home_banners as $index => $banner)
                    <span class="carousel-dot h-[5px] w-[36px] rounded-[2px] cursor-pointer transition-all duration-300 z-50
                         {{ $index === 0 ? 'bg-[#A60000]' : 'bg-[#D9D9D9]' }}"
                        data-index="{{ $index }}">
                    </span>
                @endforeach
            </div>
        @endif

    </div>
    {{-- contenido  --}}
        <!-- Filtros -->
        <div class="relative mt-[-100px] w-full max-w-[1366px] mx-auto px-[5%] md:px-[71px] z-[999]">
            <x-product-search :action="route('productos')" :marcas="$marcasfiltro"/>
        </div>

    {{-- prodductos  --}}
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] mt-[70px] mb-[50px] g">
        <div class="flex w-full justify-between mb-[24px]">
            <h1 class="text-[32px] font-bold">{{ $home->titulo1 }}</h1>
            <a href="{{route('productos')}}" class="flex items-center justify-center w-[122px] h-[46px] rounded-[14px] border border-[#DE2222] text-[#DE2222] text-[16px] font-bold">Ver todos</a>
        </div>
        {{-- items  --}}
        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[20px]">
            @foreach($marcas as $marca)
                <a href="{{ route('productos.categoria', $marca->id) }}" class="border border-gray-200 rounded-[13px] overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="w-full h-[262px] overflow-hidden ">
                        @if(!empty($marca->foto))
                            <img src="{{ asset($marca->foto) }}"
                                alt="{{ $marca->texto }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                Sin imagen
                            </div>
                        @endif
                    </div>

                    <hr class="border-gray-200">

                    <div class="p-4">
                        <h3 class="text-[18px] font-semibold text-gray-700 text-center">
                            {{ $marca->texto }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- foto1  --}}
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] mb-[50px]">
    <div class="relative h-full max-h-[600px] md:h-[343px] rounded-[12px] overflow-hidden">

        {{-- Imagen --}}
        <img
            src="{{ $home->foto1 }}"
            alt=""
            class="absolute inset-0 w-full h-full object-cover object-center"
        >

        {{-- Contenido --}}
        <div class="relative z-10 h-full px-[40px] max-w-[830px] flex flex-col justify-center text-white">

            <span class="text-[20px] font-bold ">
                {{ $home->foto1_texto1 }}
            </span>

            <h2 class="text-[40px] font-bold mb-[10px]">
                {{ $home->foto1_texto2 }}
            </h2>

            <p class="text-[16px] mb-[10px]">
                {{ $home->foto1_texto3 }}
            </p>

            <a
                href="{{ route('contacto') }}"
                class=" self-start  py-[14px] px-[12px] md:px-[28px]  rounded-[14px] bg-[#DE2222] text-white font-bold"
            >
                {{ $home->foto1_texto_boton }}
            </a>

        </div>
    </div>
</div>
    {{-- nosotrs  --}}
    <div class="w-full flex flex-col md:flex-row h-auto md:h-[600px]">
            {{-- foto --}}
            <div class="w-full md:w-1/2 h-full">
                <img src="{{$home->foto2}}" alt="" class="w-full h-full object-cover ">
            </div>
            {{-- texto --}}
            <div class="w-full md:w-1/2 h-full bg-[#A60000] flex flex-col px-[5%] md:px-[64px] pt-[24px] md:pt-[54px] pb-[24px]">
                <h1 class="text-[32px] text-white font-bold mb-[30px]">{{$home->titulo2}}</h1>
                <h3 class="text-[16px] text-white">{!! $home->descripcion !!}</h3>
                <a href="{{route('nosotros')}}" class="mt-[50px] flex items-center justify-center w-[110px] h-[46px] rounded-[14px] bg-white text-[#A60000] text-[16px] font-bold">Ver más</a>
            </div>
    </div>
    {{-- novedades --}}
    <div class="max-w-[1366px] mx-auto px-[5%] md:px-[71px] mt-[70px] mb-[50px]">
        <div class="flex w-full justify-between">
            <h1 class="text-[32px] font-bold">{{$home->titulo3}}</h1>
            <a href="{{route('novedades')}}" class="flex items-center justify-center w-[122px] h-[46px] rounded-[14px] border border-[#DE2222] text-[#DE2222] text-[16px] font-bold">Ver todos</a>
        </div>
        {{-- items  --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px] mt-[24px]">
             @foreach($novedades as $item)
            <div class="group w-full flex flex-col border border-[#DDDDE0] rounded-[8px]">

                <img src="{{ $item->foto }}"    
                     class="h-[260px] w-full object-cover"
                     alt="{{ $item->titulo }}">

                <div class="flex flex-col p-[24px]">

                    <h2 class="text-[18px] mb-[10px] font-bold">
                        {{ $item->titulo }}
                    </h2>

                    <div class="text-[16px] text-[#74716A]"
                         style="
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            line-height: 1.2em;
                            max-height: 2.4em;
                         ">
                        {!! $item->texto !!}
                    </div>
                    
                    <hr class="flex border-[#DDDDE0] mb-[15px] mt-[40px]">

                    <div class="flex justify-between ">
                        <a href="{{ route('novedades-detalle', ['id' => $item->id]) }}"
                           class="text-[16px] text-[#242424]/50 group-hover:text-black transition-colors duration-200">
                            Leer más
                        </a>

                        <a href="{{ route('novedades-detalle', ['id' => $item->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24"
                             viewBox="0 0 24 24"
                             class="fill-[#414047] group-hover:fill-[#DE2222] transition-colors duration-200">
                            <path d="M12 4L10.59 5.41L16.17 11H4V13H16.17L10.59 18.59L12 20L20 12L12 4Z"/>
                        </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>

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