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
     <!-- Titulo -->
    <title>@yield('title')</title>

    {{-- Metadatos dinámicos --}}
    @php
        // Obtener metadatos según la sección actual
        $currentRoute = request()->route() ? request()->route()->getName() : null;
        $metadato = \App\Models\Metadato::where('seccion', $currentRoute)->first();
    @endphp

    @if($metadato)
        <meta name="description" content="{{ $metadato->descripcion }}">
        <meta name="keywords" content="{{ $metadato->keywords }}">
    @else
        {{-- Metadatos por defecto --}}
        <meta name="description" content="GazPetrol - Comercialización de hidrocarburos, productos y servicios.">
        <meta name="keywords" content="GazPetrol, hidrocarburos, energía, combustible">
    @endif

    {{-- favicon  --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}">
    <!-- Cargar jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS  -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
     {{-- font awesome  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    {{-- google font  --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

</head>
<body class="h-full flex flex-col">


   {{-- @if($contacto_contenido->whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto_contenido->whatsapp) }}" target="_blank" class="fixed bottom-[40px] right-[25px] z-[900]">
        <img src="/assets/img/whatsapp.png" alt="Chat" class=" cursor-pointer">
    </a>
    @endif --}}
    <!-- header --> 

     <!-- navbar -->
        <nav class="fixed top-0 left-0 w-full z-50 bg-[#0A3858]">
        <div id="navbar" class="nav-layout nunitosans flex items-center text-white justify-between mx-auto px-[73px] max-w-[1366px] h-[90px] gap-[80px]">
            
            <!-- Logo -->
            <div class="h-[53px] w-[222px] flex-shrink-0">
                {{-- <a href="{{route('home')}}"><img src="{{$logos->foto_nav}}" alt="Logo" class=""></a> --}}
            </div>
            
            <!-- Botón Hamburguesa (solo en móvil) -->
            <button id="menu-toggle" class="lg:hidden text-2xl ml-auto">
            <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Contenedor del menú y botón -->
            <div class="hidden lg:flex items-center  flex-1">
            
            <!-- Menú centrado -->
            <div class="flex-1 flex justify-between mr-[80px] font-[400] text-[14px]">
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Nosotros</a>
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Comercialización de hidrocarburos</a>
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Productos</a>
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Clientes</a>
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Institucional</a>
                <a href="" class="{{ Request::is('') ? 'font-bold' : '' }}">Contacto</a>
            </div>

            <a href="" class="max-w-[225px] w-full h-[41px] border rounded-[20px] flex justify-center items-center transition
            {{ Request::is('') 
            ? 'bg-[#0A3858] text-white border-white' 
            : 'bg-white text-[#0A3858] border-[#0A3858]' }}">
               Solicitud de presupuesto
            </a>
        </div>


        <!-- Menú mobile -->
        <div id="mobile-menu" class="hidden absolute top-[90px] left-0 w-full ">
            <div class="flex flex-col items-center gap-4 bg-white py-6 shadow-md z-40">
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Nosotros</a>
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Comercialización de hidrocarburos</a>
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Productos</a>
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Clientes</a>
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Institucional</a>
                <a href="" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Contacto</a>
                
                <a href="" class="mt-4 w-[225px] h-[41px] border border-[#0A3858] rounded-[20px] bg-[#0A3858] text-white flex justify-center items-center">
                    Solicitud de presupuesto
                </a>
            </div>
        </div>
        </nav>

    <!-- contenido -->

    <div class="flex-grow">
        @yield('content')
    </div>
    
    <!-- footer  -->
    <footer class=" nunitosans text-white min-h-[348px] h-[348px]  flex flex-col justify-between bg-[#0A3858] ">

        <div class="h-full w-full max-w-[1366px] mx-auto px-[73px] pt-[45px] pb-[35px] flex flex-col lg:flex-row justify-center lg:justify-between items-center lg:items-start">
            <div class="">
                    {{-- <img src="{{$logos->foto_footer}}" alt="" class="h-[53px] w-[222px]"> --}}
            </div>
            <div class=" hidden lg:flex pt-[15px]">
                <ul class="flex flex-col gap-[30px]">
                    <h3 class="text-[20px] font-[700]">Secciones</h3>
                    <div class="flex flex-col justify-between text-[16px] h-[200px] max-w-[132px]">
                            <li><a href="" class=" hover:underline">Nosotros</a></li>
                            <li><a href="" class=" hover:underline">Comercialización de Hidrocarburos</a></li>
                            <li><a href="" class=" hover:underline">Productos</a></li>
                            <li><a href="" class=" hover:underline">Clientes</a></li>
                            <li><a href="" class=" hover:underline">Institucional</a></li>
                            <li><a href="" class=" hover:underline">Contacto</a></li>
                    </div>
                </ul>
            </div>
            <div class=" flex flex-col text-center lg:text-start pt-[15px] gap-[10px] lg:gap-[30px]">
                <h3 class="text-[20px] font-[700]">Suscribite al Newsletter</h3>
                <form action="{{route('adm.newsletter-store')}}" id="newsletterForm" method="POST" class="flex h-[45px] w-full md:w-[298px] bg-[#0A3858] border rounded-[20px] overflow-hidden">
                    @csrf
                    <input type="email" id="newsletterEmail" name="email" placeholder="Email" required
                        class="flex-1 px-4 text-white bg-[#0A3858] focus:outline-none border border-transparent rounded-[20px] transition duration-300">
                    <button type="submit" class="flex items-center justify-center px-4 bg-[#0A3858]">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

            </div>
            <div class="flex flex-col text-center lg:text-start pt-[15px] gap-[10px] lg:gap-[30px]">
                <h3 class="text-[20px] font-[700]">Contacto</h3>
                <div class=" flex flex-col items-start gap-[20px] px-[5%] lg:px-[0%]">
                    {{-- Dirección --}}
                {{-- @if($contacto_contenido->direccion)
                    <a href="https://maps.google.com/?q={{ urlencode($contacto_contenido->direccion) }}" target="_blank"
                    class="flex items-start gap-3 mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 10C20 16 12 22 12 22C12 22 4 16 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z" stroke="#FF9000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" stroke="#FF9000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="max-w-[300px]">{{ $contacto_contenido->direccion }}</span>
                    </a>
                @endif --}}

                {{-- Teléfono 1 --}}
                {{-- @if ($contacto_contenido->telefono)
                <h3 class="flex items-center gap-3 whitespace-nowrap mb-[10px]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M21.9999 16.92V19.92C22.0011 20.1985 21.944 20.4742 21.8324 20.7293C21.7209 20.9845 21.5572 21.2136 21.352 21.4019C21.1468 21.5901 20.9045 21.7335 20.6407 21.8227C20.3769 21.9119 20.0973 21.9451 19.8199 21.92C16.7428 21.5856 13.7869 20.5341 11.1899 18.85C8.77376 17.3147 6.72527 15.2662 5.18993 12.85C3.49991 10.2412 2.44818 7.271 2.11993 4.18C2.09494 3.90347 2.12781 3.62476 2.21643 3.36163C2.30506 3.09849 2.4475 2.85669 2.6347 2.65162C2.82189 2.44655 3.04974 2.28271 3.30372 2.17052C3.55771 2.05834 3.83227 2.00026 4.10993 2H7.10993C7.59524 1.99523 8.06572 2.16708 8.43369 2.48353C8.80166 2.79999 9.04201 3.23945 9.10993 3.72C9.23656 4.68007 9.47138 5.62273 9.80993 6.53C9.94448 6.88793 9.9736 7.27692 9.89384 7.65088C9.81408 8.02485 9.6288 8.36811 9.35993 8.64L8.08993 9.91C9.51349 12.4135 11.5864 14.4864 14.0899 15.91L15.3599 14.64C15.6318 14.3711 15.9751 14.1859 16.3491 14.1061C16.723 14.0263 17.112 14.0555 17.4699 14.19C18.3772 14.5286 19.3199 14.7634 20.2799 14.89C20.7657 14.9585 21.2093 15.2032 21.5265 15.5775C21.8436 15.9518 22.0121 16.4296 21.9999 16.92Z" stroke="#FF9000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="">
                    <span>{{ $contacto_contenido->telefono }}</span> | <span>{{ $contacto_contenido->telefono2 }}</span>
                    </div>
                </h3>
                @endif --}}
                
                {{-- Mail --}}
                {{-- @if ($contacto_contenido->mail)
                    <div
                    class="flex  gap-3 whitespace-nowrap mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4Z" stroke="#FF9000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 7L13.03 12.7C12.7213 12.8934 12.3643 12.996 12 12.996C11.6357 12.996 11.2787 12.8934 10.97 12.7L2 7" stroke="#FF9000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex flex-col">
                        <a href="mailto:{{ $contacto_contenido->mail }}">{{ $contacto_contenido->mail }}</a>
                        <a href="mailto:{{ $contacto_contenido->mail2 }}">{{ $contacto_contenido->mail2 }}</a>
                        <a href="mailto:{{ $contacto_contenido->mail3 }}">{{ $contacto_contenido->mail3 }}</a>
                        </div>
                    </div>
                @endif --}}

                {{-- WhatsApp --}}
                {{-- @if ($contacto_contenido->whatssap)
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $contacto_contenido->whatssap) }}" target="_blank"
                    class="flex items-center gap-2 whitespace-nowrap mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M17.0076 2.90097C13.1316 -0.970609 6.85625 -0.965179 2.9857 2.9064C-0.179142 6.07751 -0.830567 10.9808 1.40599 14.8632L0 20L5.25483 18.6154C6.70968 19.4081 8.33824 19.8208 9.99394 19.8208C15.4333 19.9077 19.9119 15.5691 19.9987 10.1283C20.0422 7.40786 18.9565 4.79061 17.0076 2.90097ZM9.99937 18.1538C8.52281 18.1538 7.07339 17.7574 5.80311 17.0081L5.50454 16.8289L2.38856 17.6488L3.21912 14.608L3.0237 14.2931C0.656853 10.3944 1.89999 5.31732 5.79225 2.94984C9.68995 0.582368 14.7656 1.82583 17.1325 5.71913C17.8979 6.98432 18.3159 8.4287 18.3322 9.91109C18.2833 14.4777 14.5702 18.1538 9.99937 18.1538ZM14.5159 11.9799C14.2716 11.855 13.0502 11.2577 12.8222 11.1763C12.5942 11.0948 12.4314 11.0514 12.2631 11.3012C12.0948 11.5509 11.6225 12.1048 11.4759 12.2731C11.3294 12.4415 11.1882 12.4632 10.9385 12.3383C9.48366 11.6107 8.52824 11.0405 7.56739 9.38981C7.31224 8.94998 7.82253 8.98256 8.29481 8.03774C8.35995 7.89656 8.35452 7.73366 8.2731 7.60334C8.21338 7.47845 7.71396 6.2567 7.50767 5.76258C7.30682 5.27931 7.10053 5.3499 6.94853 5.33904C6.80739 5.32818 6.6391 5.32818 6.47625 5.32818C6.22111 5.33361 5.98225 5.44763 5.81397 5.63768C5.2494 6.17525 4.93454 6.92459 4.9454 7.70651C5.02683 8.64047 5.37968 9.53099 5.95511 10.2695C6.07997 10.4324 7.7031 12.9356 10.1894 14.0107C11.7636 14.6895 12.3771 14.7492 13.1642 14.6297C13.6419 14.5592 14.6299 14.0324 14.8362 13.4514C14.9991 13.0822 15.0479 12.6695 14.9773 12.2731C14.9176 12.1645 14.7548 12.0994 14.5105 11.9799H14.5159Z" fill="#29176D"/>
                        </svg>
                        <span>{{ $contacto_contenido->whatssap }}</span>
                    </a>
                @endif --}}
                </div>
            </div>
        </div>
        
    </footer>
    <div class="relative box-border min-h-[67px] h-[67px] flex justify-between items-center  bg-[#0A3858] overflow-hidden">
        <!-- overlay -->
        <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>

        <!-- contenido -->
        <div class="w-full max-w-[1366px] mx-auto px-[73px] flex justify-between items-center  text-[14px]">
            <p class="text-white">© Copyright &copy;<?php echo date("Y");?> GazPetrol. Todos los derechos reservados</p>
            <a href="https://osole.com.ar/" class="text-white">By <span>Osole</span></a>
        </div>
    </div>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor 5 FULL BUILD -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

    @yield('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggle.addEventListener('click', () => {
            // Mostrar / ocultar el menú móvil
            mobileMenu.classList.toggle('hidden');
        });

    });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.getElementById('newsletterForm');
    const emailInput = document.getElementById('newsletterEmail');

    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir recarga de página

        const formData = new FormData(this);

        // Deshabilitar botón mientras se procesa
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Suscripción exitosa!',
                    text: data.message,
                    confirmButtonColor: '#5FBB46'
                });
                emailInput.value = ''; // Limpiar campo
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#0A3858'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error. Por favor, intenta nuevamente.',
                confirmButtonColor: '#0A3858'
            });
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        });
    });
});
</script>
</body>
</html>