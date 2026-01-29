@php
    // $home_banners = App\Models\HomeBanner::where('active', true)->orderBy('orden')->get();
    // $home = App\Models\Home::first();

    // $novedades = App\Models\Novedade::where('active', true)
    //     ->where('destacado', true)
    //     ->orderBy('orden')
    //     ->get(); 
@endphp

@extends('layouts.web')

@section('title', 'Inicio')
@section('content')

<div class="roboto">
    {{-- CARRUSEL BANNER --}}
    {{-- <div id="bannerCarousel" class="relative w-full h-[768px] overflow-hidden z-10">

        @foreach($home_banners as $index => $banner)
            <div class="carousel-slide absolute inset-0 transition-opacity duration-500 
                {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"> --}}
                
                {{-- VIDEO O IMAGEN --}}
                {{-- @if (($banner->tipo_medio ?? 'imagen') === 'video')
                    <video class="absolute top-0 left-0 w-full h-full object-cover" autoplay muted loop playsinline>
                        <source src="{{ asset($banner->video ?? $banner->foto) }}" type="video/mp4">
                        Tu navegador no soporta video HTML5.
                    </video>
                @else
                    <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center"
                        style="background-image: url('{{ asset($banner->foto) }}')">
                    </div>
                @endif

                <div class="absolute inset-0 bg-black/40 z-5"></div> --}}

                {{-- CONTENIDO (MISMO DISEÑO QUE TENÍAS) --}}
                {{-- <div class="relative h-full max-w-[1366px] mx-auto px-[5%] md:px-[71px] flex flex-col items-center text-white z-20">
                    <h3 class="text-[48px] font-semibold  mt-[304px]">
                        {{ $banner->titulo }}
                    </h3>

                    <h1 class="text-[20px]">
                        {!! $banner->texto !!}
                    </h1>
                </div>
            </div>
        @endforeach --}}

        {{-- BARRAS/PUNTOS DEL CARRUSEL --}}
        {{-- @if ($home_banners->count() > 1)
            <div class="absolute bottom-[80px] left-0 right-0 flex justify-center gap-[8px] z-30">
                @foreach ($home_banners as $index => $banner)
                    <span class="carousel-dot h-[6px] w-[44px] bg-white/40 cursor-pointer transition-all duration-300 z-50
                        {{ $index === 0 ? 'bg-white' : '' }}"
                        data-index="{{ $index }}">
                    </span>
                @endforeach
            </div>
        @endif --}}

    </div>
    {{-- contenido  --}}

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
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/40');
                });
            }
            
            // Mostrar slide actual
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
            slides[index].style.zIndex = '10';
            
            // Activar dot actual
            if (dots && dots[index]) {
                dots[index].classList.remove('bg-white/40');
                dots[index].classList.add('bg-white');
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