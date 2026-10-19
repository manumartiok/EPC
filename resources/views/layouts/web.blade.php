@php
    $contacto_contenido = App\Models\Contacto::first();
    $logos = App\Models\Logo::first(); 
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @php
        $currentRoute = request()->route() ? request()->route()->getName() : null;
        $metadato = \App\Models\Metadato::where('seccion', $currentRoute)->first();
    @endphp

    @if($metadato)
        <meta name="description" content="{{ $metadato->descripcion }}">
        <meta name="keywords" content="{{ $metadato->keywords }}">
    @else
        <meta name="description" content="Resortes Generales">
        <meta name="keywords" content="Resortes Generales">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body class="h-full flex flex-col">

    {{-- @if($contacto_contenido->whatssap)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto_contenido->whatsspp) }}" target="_blank" class="fixed bottom-[40px] right-[25px] z-[900]">
        <img src="/assets/img/whatsapp.png" alt="Chat" class=" cursor-pointer">
    </a>
    @endif --}}

    <!-- navbar -->
    @php
        $navTransparente = str_contains($navbarClass ?? '', 'navbar-home');
        $iconoNav = $navTransparente ? 'brightness-0 invert' : '';
    @endphp
    <nav class="top-0 left-0 w-full z-50 {{ $navTransparente ? 'bg-white/[0.03] text-white' : 'bg-white' }} shadow-[0px_4px_4px_0px_rgba(0,0,0,0.05)] {{ $navbarClass ?? 'relative' }}">
        <div id="navbar" class="nav-layout letra relative flex items-center justify-between mx-auto px-[5%] lg:px-[71px] max-w-[1366px] h-[90px]">

            <!-- Logo -->
            <a href="{{route('home')}}" class="flex-shrink-0">
                <img src="{{ $navTransparente ? $logos->foto_nav : $logos->foto_nav2 }}" alt="Logo" class="h-[56px] w-auto">
            </a>

            <!-- Botón Hamburguesa (solo en móvil) -->
            <button id="menu-toggle" class="lg:hidden text-2xl ml-auto {{ $navTransparente ? 'text-white' : 'text-[#303030]' }}">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Menú y accesos (desktop) -->
            <div class="hidden lg:flex items-center gap-[24px] text-[16px] {{ $navTransparente ? 'text-white' : 'text-[#303030]' }}">
                <div class="flex items-center">
                    <a href="{{ route('nosotros') }}" class="p-[10px] {{ Request::is('nosotros') ? 'font-bold' : '' }}">NOSOTROS</a>
                    <a href="{{ route('productos') }}" class="p-[10px] {{ Request::is('productos') ? 'font-bold' : '' }}">PRODUCTOS</a>
                    <a href="{{ route('calidad') }}" class="p-[10px] {{ Request::is('calidad') ? 'font-bold' : '' }}">CALIDAD</a>
                    <a href="{{ route('catalogo') }}" class="p-[10px] {{ Request::is('catalogo') ? 'font-bold' : '' }}">CATALOGO</a>
                    <a href="{{ route('novedades') }}" class="p-[10px] {{ Request::is('novedades') ? 'font-bold' : '' }}">NOVEDADES</a>
                    <a href="{{ route('contacto') }}" class="p-[10px] {{ Request::is('contacto') ? 'font-bold' : '' }}">CONTACTO</a>
                </div>

                <div class="flex items-center gap-[21px]">
                    <button type="button" id="nav-search-toggle" aria-label="Buscar" aria-expanded="false" aria-controls="nav-search">
                        <img src="{{ asset('assets/img/nav/search.svg') }}" alt="" class="{{ $iconoNav }} w-[24px] h-[24px]">
                    </button>
                    <img src="{{ asset('assets/img/nav/divider.svg') }}" alt="" class="{{ $iconoNav }} h-[25.5px] w-px">
                    <div class="flex items-center gap-[11px]">
                        <a href="{{ $contacto_contenido?->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><img src="{{ asset('assets/img/nav/facebook.svg') }}" alt="" class="{{ $iconoNav }} w-[18px] h-[18px]"></a>
                        <a href="{{ $contacto_contenido?->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><img src="{{ asset('assets/img/nav/instagram.svg') }}" alt="" class="{{ $iconoNav }} w-[18px] h-[18px]"></a>
                    </div>
                </div>
            </div>

            <!-- Buscador de productos (desktop): se abre con la lupa -->
            <form id="nav-search" action="{{ route('productos') }}" method="GET"
                  class="hidden absolute top-full right-[5%] lg:right-[71px] z-50 w-[320px] max-w-[90vw] items-center gap-[8px] bg-white border border-[#DEDFE0] shadow-[0px_4px_4px_0px_rgba(0,0,0,0.05)] px-[16px] h-[50px]">
                <button type="submit" aria-label="Buscar productos" class="shrink-0 hover:scale-100">
                    <img src="{{ asset('assets/img/nav/search.svg') }}" alt="" class="w-[24px] h-[24px]">
                </button>
                <input type="text" name="buscar" id="nav-search-input" value="{{ request()->routeIs('productos') ? request('buscar') : '' }}" placeholder="Buscar por productos" autocomplete="off"
                       class="w-full min-w-0 text-[14px] leading-[1.5] text-[#303030] placeholder:text-[#303030]/50 bg-transparent outline-none">
            </form>

            <!-- Menú mobile -->
            <div id="mobile-menu" class="hidden absolute top-[90px] left-0 w-full z-40">
                <div class="flex flex-col items-center gap-4 bg-white py-6 shadow-md text-[#303030]">
                    <a href="{{ route('nosotros') }}" class="text-[16px] ">NOSOTROS</a>
                    <a href="{{ route('productos') }}" class="text-[16px]">PRODUCTOS</a>
                    <a href="{{ route('calidad') }}" class="text-[16px]">CALIDAD</a>
                    <a href="{{ route('catalogo') }}" class="text-[16px]">CATALOGO</a>
                    <a href="{{ route('novedades') }}" class="text-[16px]">NOVEDADES</a>
                    <a href="{{ route('contacto') }}" class="text-[16px]">CONTACTO</a>

                    {{-- Buscador de productos (mobile) --}}
                    <form action="{{ route('productos') }}" method="GET" class="flex items-center gap-[8px] w-[80%] max-w-[320px] h-[44px] border border-[#DEDFE0] px-[12px]">
                        <button type="submit" aria-label="Buscar productos" class="shrink-0 hover:scale-100">
                            <img src="{{ asset('assets/img/nav/search.svg') }}" alt="" class="w-[20px] h-[20px]">
                        </button>
                        <input type="text" name="buscar" placeholder="Buscar por productos" autocomplete="off"
                               class="w-full min-w-0 text-[14px] text-[#303030] placeholder:text-[#303030]/50 bg-transparent outline-none">
                    </form>
                </div>
            </div>
        </div>
    </nav>

    @include('components.auth-modals')

    <!-- ===================== CONTENIDO ===================== -->
    <div class="flex-grow">
        @yield('content')
    </div>

    <!-- ===================== FOOTER ===================== -->
    <footer class="letra text-white bg-[#003879]">
        <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[74px] pt-[60px] lg:pt-[85px] pb-[60px] grid grid-cols-1 lg:grid-cols-[277px_344px_316px_1fr] gap-[40px] lg:gap-0">

            {{-- logo + certificado --}}
            <div class="lg:pl-[30px] flex flex-col items-center lg:items-start">
                <img src="{{$logos->foto_footer}}" alt="EPC" class="w-[121px] h-auto">
                <div class="mt-[56px] flex flex-col items-center w-[104px]">
                    <div class="flex gap-[13px] items-center">
                        <div class="w-[46px] h-[46px] bg-white" style="-webkit-mask: url('{{ asset('assets/img/footer/iram-2.png') }}') center/contain no-repeat; mask: url('{{ asset('assets/img/footer/iram-2.png') }}') center/contain no-repeat;"></div>
                        <div class="w-[45px] h-[45px] bg-white" style="-webkit-mask: url('{{ asset('assets/img/footer/iram-1.png') }}') center/contain no-repeat; mask: url('{{ asset('assets/img/footer/iram-1.png') }}') center/contain no-repeat;"></div>
                    </div>
                    <p class="text-[12px] leading-[1.5] mt-[4px] whitespace-nowrap">CERTIFICADO IRAM</p>
                </div>
            </div>

            {{-- secciones --}}
            <div>
                <h3 class="text-[20px] leading-[1.2] font-bold uppercase">Secciones</h3>
                <div class="mt-[27px] flex gap-[50px] text-[16px] leading-[2]">
                    <ul>
                        <li><a href="{{ route('nosotros') }}" class="hover:underline">Nosotros</a></li>
                        <li><a href="{{ route('productos') }}" class="hover:underline">Productos</a></li>
                        <li><a href="{{ route('calidad') }}" class="hover:underline">Calidad</a></li>
                    </ul>
                    <ul>
                        <li><a href="{{ route('catalogo') }}" class="hover:underline">Catálogo</a></li>
                        <li><a href="{{ route('novedades') }}" class="hover:underline">Novedades</a></li>
                        <li><a href="{{ route('contacto') }}" class="hover:underline">Contacto</a></li>
                    </ul>
                </div>
            </div>

            {{-- newsletter + redes --}}
            <div>
                <h3 class="text-[20px] leading-[1.2] font-bold uppercase">Suscribite al newsletter</h3>
                <form action="{{route('adm.newsletter-store')}}" id="newsletterForm" method="POST" class="mt-[27px] flex items-center h-[40px] w-full max-w-[277px] border border-[#DEDFE0]">
                    @csrf
                    <input type="email" id="newsletterEmail" name="email" placeholder="Email" required
                        class="flex-1 min-w-0 h-full px-[12px] text-[14px] text-white placeholder-white bg-transparent focus:outline-none">
                    <button type="submit" class="px-[12px] h-full flex items-center" aria-label="Suscribirse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M8 12H16M12 8L16 12L12 16" stroke="#086AFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                    </button>
                </form>

                <h3 class="mt-[56px] text-[20px] leading-[1.2] font-bold uppercase">Seguinos en</h3>
                <div class="mt-[24px] flex gap-[5px]">
                    <a href="{{ $contacto_contenido?->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <img src="{{ asset('assets/img/footer/facebook.svg') }}" alt="" class="w-[24px] h-[24px]">
                    </a>
                    <a href="{{ $contacto_contenido?->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <img src="{{ asset('assets/img/footer/instagram.svg') }}" alt="" class="w-[24px] h-[24px]">
                    </a>
                </div>
            </div>

            {{-- contacto --}}
            <div>
                <h3 class="text-[20px] leading-[1.2] font-bold uppercase">Contacto</h3>
                <div class="mt-[27px] flex flex-col gap-[18px] text-[16px] leading-[1.5]">
                    @if($contacto_contenido?->direccion)
                    <div class="flex items-start gap-[11px]">
                        <img src="{{ asset('assets/img/footer/map-pin.svg') }}" alt="" class="w-[20px] h-[20px] mt-[2px]">
                        <span class="max-w-[260px]">{{ $contacto_contenido->direccion }}</span>
                    </div>
                    @endif
                    @if($contacto_contenido?->mail)
                    <div class="flex items-center gap-[11px]">
                        <img src="{{ asset('assets/img/footer/mail.svg') }}" alt="" class="w-[20px] h-[20px]">
                        <a href="mailto:{{ $contacto_contenido->mail }}">{{ $contacto_contenido->mail }}</a>
                    </div>
                    @endif
                    @if($contacto_contenido?->telefono)
                    <div class="flex items-center gap-[11px]">
                        <img src="{{ asset('assets/img/footer/phone.svg') }}" alt="" class="w-[20px] h-[20px]">
                        <span>{{ $contacto_contenido->telefono }}</span>
                    </div>
                    @endif
                    @if($contacto_contenido?->whatssap)
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $contacto_contenido->whatssap) }}" target="_blank" class="flex items-center gap-[11px]">
                        <img src="{{ asset('assets/img/footer/whatsapp.svg') }}" alt="" class="w-[20px] h-[20px]">
                        <span>{{ $contacto_contenido->whatssap }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- barra inferior --}}
        <div class="bg-[#083E7D] h-auto min-h-[70px] py-[20px] lg:py-0 lg:h-[70px]">
            <div class="max-w-[1366px] mx-auto px-[5%] lg:px-[74px] h-full flex flex-col lg:flex-row gap-[8px] justify-between items-center text-[14px] leading-[1.5]">
                <p>© Copyright {{ date('Y') }} EPC. Energía sin límites. Todos los derechos reservados</p>
                <a href="https://osole.com.ar/">by <span class="font-bold">Osole</span></a>
            </div>
        </div>
    </footer>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

    @yield('scripts')

    {{-- ===================== SCRIPTS ===================== --}}

    {{-- Toggle menú móvil --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle     = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        toggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    });
    </script>

    {{-- Buscador de productos del nav --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const boton = document.getElementById('nav-search-toggle');
        const panel = document.getElementById('nav-search');
        const input = document.getElementById('nav-search-input');
        if (!boton || !panel) return;

        const abrir = () => {
            panel.classList.remove('hidden');
            panel.classList.add('flex');
            boton.setAttribute('aria-expanded', 'true');
            input.focus();
        };
        const cerrar = () => {
            panel.classList.add('hidden');
            panel.classList.remove('flex');
            boton.setAttribute('aria-expanded', 'false');
        };

        boton.addEventListener('click', () => panel.classList.contains('hidden') ? abrir() : cerrar());
        document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });
        document.addEventListener('click', e => {
            if (!panel.contains(e.target) && !boton.contains(e.target)) cerrar();
        });
        panel.addEventListener('submit', e => {
            if (!input.value.trim()) { e.preventDefault(); input.focus(); }
        });
    });
    </script>

    {{-- Newsletter --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form       = document.getElementById('newsletterForm');
        const emailInput = document.getElementById('newsletterEmail');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData  = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const origHTML  = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            fetch(this.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: '¡Suscripción exitosa!', text: data.message, confirmButtonColor: '#DE2222' });
                        emailInput.value = '';
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#DE2222' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error. Intentá nuevamente.', confirmButtonColor: '#DE2222' }))
                .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = origHTML; });
        });
    });
    </script>

    @include('components.auth-modal-scripts')

</body>
</html>