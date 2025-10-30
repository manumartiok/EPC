@php
    $redes = App\Models\Red::orderBy('orden')->get(); 
    $logos = App\Models\Logo::first(); 

      $whatsapp = App\Models\Red::where('icono', 'fa-brands fa-whatsapp') // o 'nombre', si preferís
                    ->where('active', 1)
                    ->first();
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

    @if($whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp->url) }}" target="_blank" class="fixed bottom-[40px] right-[25px] z-50">
        <img src="/assets/img/whatsapp.png" alt="Chat" class=" cursor-pointer">
    </a>
    @endif
    <!-- header --> 

     <!-- navbar -->
        <nav class="fixed top-0 left-0 w-full z-50 bg-[#0A3858]">
        <div id="navbar" class="nav-layout nunitosans flex items-center text-white justify-between mx-auto px-[73px] max-w-[1366px] h-[90px] gap-[80px]">
            
            <!-- Logo -->
            <div class="h-[53px] w-[222px] flex-shrink-0">
                <a href="{{route('home')}}"><img src="{{$logos->foto_nav}}" alt="Logo" class=""></a>
            </div>
            
            <!-- Botón Hamburguesa (solo en móvil) -->
            <button id="menu-toggle" class="lg:hidden text-2xl ml-auto">
            <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Contenedor del menú y botón -->
            <div class="hidden lg:flex items-center  flex-1">
            
            <!-- Menú centrado -->
            <div class="flex-1 flex justify-between mr-[80px] font-[400] text-[14px]">
                <a href="{{ route('nosotros') }}" class="{{ Request::is('nosotros') ? 'font-bold' : '' }}">Nosotros</a>
                <a href="{{ route('comercializacion') }}" class="{{ Request::is('comercializacion*') ? 'font-bold' : '' }}">Comercialización de hidrocarburos</a>
                <a href="{{ route('productos') }}" class="{{ Request::is('productos*') ? 'font-bold' : '' }}">Productos</a>
                <a href="{{ route('clientes') }}" class="{{ Request::is('clientes*') ? 'font-bold' : '' }}">Clientes</a>
                <a href="{{ route('institucional') }}" class="{{ Request::is('institucional*') ? 'font-bold' : '' }}">Institucional</a>
                <a href="{{ route('contacto') }}" class="{{ Request::is('contacto') ? 'font-bold' : '' }}">Contacto</a>
            </div>

            <a href="{{ route('solicitud') }}" class="max-w-[225px] w-full h-[41px] border rounded-[20px] flex justify-center items-center transition
            {{ Request::is('solicitud') 
            ? 'bg-[#0A3858] text-white border-white' 
            : 'bg-white text-[#0A3858] border-[#0A3858]' }}">
               Solicitud de presupuesto
            </a>
        </div>


        <!-- Menú mobile -->
        <div id="mobile-menu" class="hidden absolute top-[90px] left-0 w-full ">
            <div class="flex flex-col items-center gap-4 bg-white py-6 shadow-md z-40">
                <a href="{{ route('nosotros') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Nosotros</a>
                <a href="{{ route('comercializacion') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Comercialización de hidrocarburos</a>
                <a href="{{ route('productos') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Productos</a>
                <a href="{{ route('clientes') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Clientes</a>
                <a href="{{ route('institucional') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Institucional</a>
                <a href="{{ route('contacto') }}" class="text-[#0A3858] text-[16px] font-semibold hover:text-[#5FBB46]">Contacto</a>
                
                <a href="{{ route('solicitud') }}" class="mt-4 w-[225px] h-[41px] border border-[#0A3858] rounded-[20px] bg-[#0A3858] text-white flex justify-center items-center">
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
                    <img src="{{$logos->foto_footer}}" alt="" class="h-[53px] w-[222px]">
            </div>
            <div class=" hidden lg:flex pt-[15px]">
                <ul class="flex flex-col gap-[30px]">
                    <h3 class="text-[20px] font-[700]">Secciones</h3>
                    <div class="flex flex-col justify-between text-[16px] h-[200px] max-w-[132px]">
                            <li><a href="{{route('nosotros')}}" class=" hover:underline">Nosotros</a></li>
                            <li><a href="{{route('comercializacion')}}" class=" hover:underline">Comercialización de Hidrocarburos</a></li>
                            <li><a href="{{route('productos')}}" class=" hover:underline">Productos</a></li>
                            <li><a href="{{route('clientes')}}" class=" hover:underline">Clientes</a></li>
                            <li><a href="{{route('institucional')}}" class=" hover:underline">Institucional</a></li>
                            <li><a href="{{route('contacto')}}" class=" hover:underline">Contacto</a></li>
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
                    @foreach($redes as $red)
                    <div class="flex gap-[10px] items-center">
                       <i class="{{$red->icono}} text-white "></i><a href="{{$red->url}}" class=" hover:underline hover:scale-100 max-w-[200px]"><h3 class=" ">{{$red->nombre}}</h3></a> 
                    </div>
                    @endforeach
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