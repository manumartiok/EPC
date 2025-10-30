<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Panel Admin')</title>
  {{-- favicon  --}}
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}">
  {{-- jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  {{-- tailwind  --}}
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- font awesome  --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  {{-- Quill.js CSS --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  {{-- Css --}}
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

  <!-- Vue -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>


   <script src="{{ asset('js/vue.js') }}" defer></script>
   <script src="{{ asset('js/ckeditor-custom.js') }}" defer></script>
  <style>
    .sidebar-transition {
      transition: all 0.3s ease-in-out;
    }

    #sidebar.w-0 {
      padding: 0 !important;
    }

    #sidebar.w-0 > div {
        opacity: 0;
        pointer-events: none;
      }
    .user-menu {
      display: none;
      position: absolute;
      top: 100%;
      right: 0;
      z-index: 1000;
    }
    .user-menu.active {
      display: block;
    }
  </style>
</head>
<body class="bg-gray-100">
  
  <div class="flex h-screen">
    @include('layouts.sidebar')
    <div class="flex-1 flex flex-col">
      @include('layouts.navbar')
      <main class="flex-1 overflow-auto p-6">
           {{-- Mensajes de feedback --}}
        @if(session('success'))
                <div id="feedback-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-700 cursor-pointer" role="button" onclick="this.parentElement.parentElement.remove();" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.36 5.652a.5.5 0 1 0-.707.707L9.293 10l-3.64 3.64a.5.5 0 0 0 .707.707L10 10.707l3.64 3.64a.5.5 0 0 0 .707-.707L10.707 10l3.64-3.64a.5.5 0 0 0 0-.707z"/></svg>
                    </span>
                </div>
            @endif

            @if(session('error'))
                <div id="feedback-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-700 cursor-pointer" role="button" onclick="this.parentElement.parentElement.remove();" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.36 5.652a.5.5 0 1 0-.707.707L9.293 10l-3.64 3.64a.5.5 0 0 0 .707.707L10 10.707l3.64 3.64a.5.5 0 0 0 .707-.707L10.707 10l3.64-3.64a.5.5 0 0 0 0-.707z"/></svg>
                    </span>
                </div>
            @endif
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  {{-- Quill.js JS --}}
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <script>
    $(document).ready(function() {
      // Toggle Sidebar
      let sidebarOpen = true;

    $('#toggleSidebar').click(function() {
        sidebarOpen = !sidebarOpen;
        
        if (sidebarOpen) {
          $('#sidebar').removeClass('w-0 overflow-hidden').addClass('w-64');
          // Mostrar contenido
          $('#sidebar > div').removeClass('opacity-0');
        } else {
          $('#sidebar').removeClass('w-64').addClass('w-0 overflow-hidden');
          // Ocultar contenido
          $('#sidebar > div').addClass('opacity-0');
        }
      });
      
      // Toggle User Menu
      $('#userMenuBtn').click(function(e) {
        e.stopPropagation();
        $('#userMenu').toggleClass('active');
      });

      // Close menu when clicking outside
      $(document).click(function(e) {
        if (!$(e.target).closest('#userMenuBtn, #userMenu').length) {
          $('#userMenu').removeClass('active');
        }
      });

      // Close menu when clicking a link
      $('#userMenu a').click(function() {
        $('#userMenu').removeClass('active');
      });
    });

    /**
 * Habilita el ordenamiento drag & drop en cualquier tabla.
 * @param {string} selector - El selector CSS del tbody (ej: '#galeria-fotos')
 * @param {string} url - La URL a la que se enviará el orden actualizado
 */
function activarOrdenDragDrop(selector, url) {
    const tbody = document.querySelector(selector);
    if (!tbody) return;

    Sortable.create(tbody, {
        animation: 150,
        handle: '.cursor-move',
        draggable: 'tr', // seguimos dejando todas las filas arrastrables
        onEnd: function () {
            let orden = [];

            // Solo actualizamos las filas que NO sean encabezados de categoría
         tbody.querySelectorAll('tr:not(.no-ordena)').forEach((fila, index) => {
            const letra = String.fromCharCode(65 + Math.floor(index / 26)); // A, B, C...
            const subletra = String.fromCharCode(65 + (index % 26)); // A, B, C...
            const nuevaLetra = letra + subletra; // AA, AB, AC, AD...

            const celdaOrden = fila.querySelector('td:first-child');
            if (celdaOrden) celdaOrden.textContent = nuevaLetra;

            orden.push({
                id: fila.dataset.id,
                orden: nuevaLetra
            });
        });

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ orden })
            })
            .then(r => r.json())
            .then(() => console.log('Orden actualizado correctamente'))
            .catch(err => console.error('Error al actualizar el orden:', err));
        }
    });
}
  </script>

   @stack('scripts')  {{-- AGREGAR ESTA LÍNEA --}}
</body>
</html>