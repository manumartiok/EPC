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
        <meta name="description" content="Resortes Generales">
        <meta name="keywords" content="Resortes Generales">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">


</head>
<body class="h-full flex flex-col">


   @if($contacto_contenido->whatssap)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto_contenido->whatsspp) }}" target="_blank" class="fixed bottom-[40px] right-[25px] z-[900]">
        <img src="/assets/img/whatsapp.png" alt="Chat" class=" cursor-pointer">
    </a>
    @endif
    <!-- header --> 

     <!-- navbar -->
        <nav class="relative top-0 left-0 w-full z-50 bg-white shadow-md">
        <div id="navbar" class="nav-layout letra flex items-center  justify-between mx-auto px-[73px] max-w-[1366px] h-[100px] gap-[80px]">
            
            <!-- Logo -->
            <div class="h-[68px] w-[121px] flex-shrink-0">
                <a href="{{route('home')}}"><img src="{{$logos->foto_nav}}" alt="Logo" class=""></a>
            </div>
            
            <!-- Botón Hamburguesa (solo en móvil) -->
            <button id="menu-toggle" class="lg:hidden text-2xl ml-auto">
            <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Contenedor del menú y botón -->
            <div class="hidden lg:flex items-center  flex-1">
            
            <!-- Menú centrado -->
            <div class="flex-1 flex justify-end  font-[400] text-[16px] gap-4 mr-4">
                <a href="{{route('web-productos')}}" class="{{ Request::is('clientes/productos') ? 'font-bold' : '' }}">Productos</a>
                <a href="{{route('web-carrito')}}" class="{{ Request::is('clientes/carrito') ? 'font-bold' : '' }}">Carrito</a>
                <a href="{{route('web-info')}}" class="{{ Request::is('clientes/informacion-pago') ? 'font-bold' : '' }}">Info Pago</a>
                <a href="{{route('web-pedidos')}}" class="{{ Request::is('clientes/pedidos') ? 'font-bold' : '' }}">Mis pedidos</a>
                <a href="{{route('web-lista')}}" class="{{ Request::is('clientes/lista') ? 'font-bold' : '' }}">Lista de precios</a>
            </div>
            @auth('web_usuario')
                <button id="btn-usuario" type="button" class="flex items-center gap-2 h-[46px] px-3 rounded-[14px] border-2 border-[#DE2222] text-[#DE2222] text-[14px] font-semibold max-w-[220px] truncate">
                    <span class="truncate">{{ auth('web_usuario')->user()->usuario ?? auth('web_usuario')->user()->email }}</span>
                </button>
            @else
                <button id="btn-usuario" class="flex items-center justify-center h-[46px] w-[42px] rounded-[14px] border border-[#DE2222]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M12 14C16 14 19 16 19 17V19H5V17C5 16 8 14 12 14Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            @endauth
        </div>


        <!-- Menú mobile -->
        <div id="mobile-menu" class="hidden absolute top-[90px] left-0 w-full ">
            <div class="flex flex-col items-center gap-4 bg-white py-6 shadow-md z-40">
                <a href="{{route('web-productos')}}" class=" text-[16px] font-semibold hover:text-[#5FBB46]">Productos</a>
                <a href="{{route('web-carrito')}}" class=" text-[16px] font-semibold hover:text-[#5FBB46]">Carrito</a>
                <a href="{{ route('web-info') }}" class="text-[16px] font-semibold uppercase ">Info de pagos</a>
                <a href="{{ route('web-pedidos') }}" class="text-[16px] font-semibold uppercase ">Mis pedidos</a>
                <a href="{{route('web-lista')}}" class=" text-[16px] font-semibold hover:text-[#5FBB46]">Lista de precios</a>

                 <!-- Botón de usuario para mobile -->
        @auth('web_usuario')
            <button id="btn-usuario-mobile" type="button" class="flex items-center justify-center gap-2 h-[46px] px-4 rounded-[14px] border-2 border-[#DE2222] text-[#DE2222] text-[14px] font-semibold max-w-[220px] truncate">
                <span class="truncate">{{ auth('web_usuario')->user()->usuario ?? auth('web_usuario')->user()->email }}</span>
            </button>
        @else
            <button id="btn-usuario-mobile" class="flex items-center justify-center h-[46px] w-[42px] rounded-[14px] border border-[#DE2222]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 14C16 14 19 16 19 17V19H5V17C5 16 8 14 12 14Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        @endauth
                
            </div>
        </div>
            </nav>

        @include('components.auth-modals')

    <!-- contenido -->

    <div class="flex-grow">
        @yield('content')
    </div>
    
    <!-- footer  -->
    <footer class=" letra text-white h-auto  flex flex-col justify-between bg-[#000000] ">

        <div class="h-full w-full max-w-[1366px] mx-auto px-[73px] pt-[85px] pb-[50px] flex flex-col lg:flex-row justify-center lg:justify-between items-center lg:items-start">
            <div class="">
                    <img src="{{$logos->foto_footer}}" alt="" class="h-[119px] w-[212px]">
            </div>
            <div class=" hidden lg:flex pt-[15px]">
                <ul class="flex flex-col gap-[30px]">
                    <h3 class="text-[20px] font-[700]">Secciones</h3>
                    <div class="flex flex-col md:flex-row gap-[50px] text-[16px]">
                        <div class="flex flex-col gap-[15px]">
                            <li><a href="{{route('nosotros')}}" class=" hover:underline">Nosotros</a></li>
                            <li><a href="" class=" hover:underline">Productos</a></li>
                            <li><a href="{{route('desarrollo')}}" class=" hover:underline">Desarrollo a medida</a></li>
                        </div>
                        <div class="flex flex-col gap-[15px]">
                            <li><a href="{{route('novedades')}}" class=" hover:underline">Novedades</a></li>
                            <li><a href="{{route('contacto')}}" class=" hover:underline">Contacto</a></li>
                        </div>
                    </div>
                </ul>
            </div>
            <div class=" flex flex-col text-center lg:text-start pt-[15px] gap-[10px] lg:gap-[30px]">
                <h3 class="text-[20px] font-[700]">Suscribite al Newsletter</h3>
                <form action="{{route('adm.newsletter-store')}}" id="newsletterForm" method="POST" class="flex h-[45px] w-full md:w-[298px]  border rounded-[4px] overflow-hidden">
                    @csrf
                    <input type="email" id="newsletterEmail" name="email" placeholder="Email" required
                        class="flex-1 px-4 text-white bg-[#000000] focus:outline-none border border-transparent rounded-[4px] transition duration-300">
                    <button type="submit" class="flex items-center justify-center px-4 bg-[#000000]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M5 12H19M12 19L19 12L12 5" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                    </button>
                </form>
                <div class="flex flex-col gap-[24px]">
                    <h3 class="text-[20px] font-[700]">Redes sociales</h3>
                        <div class="flex gap-[10px] justify-center md:justify-start">

                            {{-- Instagram --}}
                            @if($contacto_contenido->instagram)
                            <a href="{{ $contacto_contenido->instagram }}"
                            target="_blank"
                            rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.5 6.5H17.51M7 2H17C19.7614 2 22 4.23858 22 7V17C22 19.7614 19.7614 22 17 22H7C4.23858 22 2 19.7614 2 17V7C2 4.23858 4.23858 2 7 2ZM16 11.37C16.1234 12.2022 15.9813 13.0522 15.5938 13.799C15.2063 14.5458 14.5931 15.1514 13.8416 15.5297C13.0901 15.9079 12.2384 16.0396 11.4078 15.9059C10.5771 15.7723 9.80976 15.3801 9.21484 14.7852C8.61992 14.1902 8.22773 13.4229 8.09407 12.5922C7.9604 11.7616 8.09207 10.9099 8.47033 10.1584C8.84859 9.40685 9.45419 8.79374 10.201 8.40624C10.9478 8.01874 11.7978 7.87659 12.63 8C13.4789 8.12588 14.2649 8.52146 14.8717 9.12831C15.4785 9.73515 15.8741 10.5211 16 11.37Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            @endif

                            {{-- Facebook --}}
                            @if($contacto_contenido->facebook)
                            <a href="{{ $contacto_contenido->facebook }}"
                            target="_blank"
                            rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" fill="white"/>
                                </svg>
                            </a>
                            @endif

                        </div>
                </div>


            </div>
            <div class="flex flex-col text-center lg:text-start pt-[15px] gap-[10px] lg:gap-[30px] max-w-[300px]">
                <h3 class="text-[20px] font-[700]">Información de contacto</h3>
                <div class=" flex flex-col items-start gap-[20px] px-[5%] lg:px-[0%]">
                    {{-- Dirección --}}
                @if($contacto_contenido->direccion)
                    <a href="https://maps.google.com/?q={{ urlencode($contacto_contenido->direccion) }}" target="_blank"
                    class="flex items-start gap-3 mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 20 20" fill="none">
  <path d="M16.6668 8.33341C16.6668 13.3334 10.0002 18.3334 10.0002 18.3334C10.0002 18.3334 3.3335 13.3334 3.3335 8.33341C3.3335 6.5653 4.03588 4.86961 5.28612 3.61937C6.53636 2.36913 8.23205 1.66675 10.0002 1.66675C11.7683 1.66675 13.464 2.36913 14.7142 3.61937C15.9644 4.86961 16.6668 6.5653 16.6668 8.33341Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M10.0002 10.8334C11.3809 10.8334 12.5002 9.71413 12.5002 8.33341C12.5002 6.9527 11.3809 5.83341 10.0002 5.83341C8.61945 5.83341 7.50016 6.9527 7.50016 8.33341C7.50016 9.71413 8.61945 10.8334 10.0002 10.8334Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                        <span class="max-w-[300px]">{{ $contacto_contenido->direccion }}</span>
                    </a>
                @endif
                
                {{-- Mail --}}
                @if ($contacto_contenido->mail)
                    <div
                    class="flex  gap-3 whitespace-nowrap mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M18.3337 5.83331L10.8587 10.5833C10.6014 10.7445 10.3039 10.83 10.0003 10.83C9.69673 10.83 9.39926 10.7445 9.14199 10.5833L1.66699 5.83331M3.33366 3.33331H16.667C17.5875 3.33331 18.3337 4.0795 18.3337 4.99998V15C18.3337 15.9205 17.5875 16.6666 16.667 16.6666H3.33366C2.41318 16.6666 1.66699 15.9205 1.66699 15V4.99998C1.66699 4.0795 2.41318 3.33331 3.33366 3.33331Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                        <div class="flex flex-col">
                        <a href="mailto:{{ $contacto_contenido->mail }}">{{ $contacto_contenido->mail }}</a>

                        </div>
                    </div>
                @endif

                      {{-- Teléfono 1 --}}
                @if ($contacto_contenido->telefono)
                <h3 class="flex items-center gap-3 whitespace-nowrap mb-[10px]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <g clip-path="url(#clip0_8_4006)">
    <path d="M18.3332 14.1V16.6C18.3341 16.8321 18.2866 17.0618 18.1936 17.2744C18.1006 17.4871 17.9643 17.678 17.7933 17.8349C17.6222 17.9918 17.4203 18.1112 17.2005 18.1856C16.9806 18.2599 16.7477 18.2875 16.5165 18.2666C13.9522 17.988 11.489 17.1118 9.32486 15.7083C7.31139 14.4289 5.60431 12.7218 4.32486 10.7083C2.91651 8.53432 2.04007 6.05914 1.76653 3.48331C1.7457 3.25287 1.77309 3.02061 1.84695 2.80133C1.9208 2.58205 2.03951 2.38055 2.1955 2.20966C2.3515 2.03877 2.54137 1.90224 2.75302 1.80875C2.96468 1.71526 3.19348 1.66686 3.42486 1.66665H5.92486C6.32928 1.66267 6.72136 1.80588 7.028 2.06959C7.33464 2.3333 7.53493 2.69952 7.59153 3.09998C7.69705 3.90003 7.89274 4.68558 8.17486 5.44165C8.28698 5.73992 8.31125 6.06407 8.24478 6.37571C8.17832 6.68735 8.02392 6.9734 7.79986 7.19998L6.74153 8.25831C7.92783 10.3446 9.65524 12.072 11.7415 13.2583L12.7999 12.2C13.0264 11.9759 13.3125 11.8215 13.6241 11.7551C13.9358 11.6886 14.2599 11.7129 14.5582 11.825C15.3143 12.1071 16.0998 12.3028 16.8999 12.4083C17.3047 12.4654 17.6744 12.6693 17.9386 12.9812C18.2029 13.2931 18.3433 13.6913 18.3332 14.1Z" stroke="#DE2222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </g>
  <defs>
    <clipPath id="clip0_8_4006">
      <rect width="20" height="20" fill="white"/>
    </clipPath>
  </defs>
</svg>
                    <div class="">
                    <span>{{ $contacto_contenido->telefono }}</span> 
                    </div>
                </h3>
                @endif

                {{-- WhatsApp --}}
                @if ($contacto_contenido->whatssap)
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $contacto_contenido->whatssap) }}" target="_blank"
                    class="flex items-center gap-2 whitespace-nowrap mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5823 11.985C14.3328 11.8608 13.1095 11.2625 12.8817 11.1792C12.6539 11.0967 12.4881 11.0558 12.3215 11.3042C12.1557 11.5508 11.6793 12.1092 11.5344 12.2742C11.3887 12.44 11.2438 12.46 10.9952 12.3367C10.7465 12.2117 9.94429 11.9508 8.99391 11.1075C8.25453 10.4509 7.75464 9.64002 7.60978 9.39169C7.46492 9.14419 7.59387 9.01002 7.71863 8.88669C7.83084 8.77585 7.96732 8.59752 8.09209 8.45335C8.21685 8.30835 8.25788 8.20502 8.34078 8.03919C8.42451 7.87419 8.38265 7.73002 8.31985 7.60585C8.25788 7.48169 7.7605 6.26252 7.55284 5.76669C7.35104 5.28419 7.14589 5.35003 6.99349 5.34169C6.8478 5.33503 6.682 5.33336 6.51621 5.33336C6.35041 5.33336 6.08079 5.39503 5.85303 5.64336C5.62444 5.89086 4.98219 6.49002 4.98219 7.70919C4.98219 8.92752 5.87313 10.105 5.99789 10.2709C6.12266 10.4359 7.75213 12.9375 10.2482 14.01C10.8428 14.265 11.3058 14.4175 11.6667 14.5308C12.2629 14.72 12.8055 14.6933 13.2342 14.6292C13.7115 14.5583 14.7063 14.03 14.9139 13.4517C15.1208 12.8733 15.1208 12.3775 15.0588 12.2742C14.9968 12.1708 14.831 12.1092 14.5815 11.985H14.5823ZM10.0423 18.1542H10.0389C8.55634 18.1544 7.10099 17.7578 5.8254 17.0058L5.52396 16.8275L2.39062 17.6458L3.22712 14.6058L3.03035 14.2942C2.20149 12.9811 1.76286 11.4615 1.76512 9.91085C1.7668 5.36919 5.47958 1.6742 10.0456 1.6742C12.2562 1.6742 14.3345 2.53253 15.897 4.08919C16.6676 4.85301 17.2785 5.76133 17.6941 6.7616C18.1098 7.76188 18.322 8.83425 18.3186 9.91668C18.3169 14.4583 14.6041 18.1542 10.0423 18.1542ZM17.086 2.9067C16.1634 1.98247 15.0657 1.24965 13.8564 0.7507C12.6472 0.251754 11.3505 -0.00339687 10.0414 3.41479e-05C4.55347 3.41479e-05 0.0854091 4.44586 0.0837344 9.91002C0.0811914 11.649 0.539563 13.3578 1.4126 14.8642L0 20L5.27861 18.6217C6.73884 19.4134 8.37519 19.8283 10.0381 19.8283H10.0423C15.5302 19.8283 19.9983 15.3825 20 9.91752C20.004 8.61525 19.7485 7.32511 19.2484 6.12172C18.7482 4.91833 18.0132 3.82559 17.086 2.9067Z" fill="#DE2222"/>
</svg>
                        <span>{{ $contacto_contenido->whatssap }}</span>
                    </a>
                @endif
                </div>
            </div>
        </div>
        
    </footer>
    <div class="relative box-border min-h-[67px] h-[67px] flex justify-between items-center  bg-[#000000] overflow-hidden">
        <!-- overlay -->
        <div class="absolute inset-0  pointer-events-none"></div>

        <!-- contenido -->
        <div class="w-full max-w-[1366px] mx-auto px-[73px] flex justify-between items-center  text-[14px] text-[#A60000]">
            <p >© Copyright &copy;<?php echo date("Y");?> Resortes generales. Todos los derechos reservados</p>
            <a href="https://osole.com.ar/" >By <span>Osole</span></a>
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

@include('components.auth-modal-scripts')
</body>
</html>