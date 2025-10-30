@php

@endphp
<aside id="sidebar" class="w-64 bg-gray-800 text-white min-h-screen p-4 sidebar-transition overflow-hidden">
  <div>
    <div class="flex flex-col items-center mb-4">
     <a href="{{route('adm.dashboard')}}"><img src="" alt="Logo" class="h-[40px] mb-[10px]"></a> 
      <h2 class="text-xl font-bold ml-2">Administrador</h2>
    </div>
  </div>
  <hr class="mx-3 my-4">
  
  <!-- Web Links -->
  <div class="mb-6">
    <ul>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-home mr-2"></i>Home</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-info-circle mr-2"></i>Nosotros</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-gas-pump mr-2"></i>Comercializacion </span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-box mr-2"></i>Productos</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Categorias</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Productos</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-person mr-2"></i>Clientes</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Clientes</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-building mr-2"></i>Institucional</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Persona</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-envelope mr-2"></i>Contacto</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
          <li><a href="{{route('adm.redes')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Redes Sociales</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-dollar mr-2"></i>Solicitar presupuesto</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
        </ul>
      </li>
       <li class="group">
        <button class="flex justify-between items-center w-full py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fa-solid fa-screwdriver-wrench mr-2"></i>Servicios</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="" class="block py-1 px-2 hover:bg-gray-600 rounded">Manejar servicios</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <hr class="mx-3 my-4">

  <!-- Configuración -->
  <div>
    <ul>
      <li><a href="{{route('adm.newsletter')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fa-solid fa-newspaper mr-2"></i>Newsletter</a></li>
      <li><a href="" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-image mr-2"></i>Logos</a></li>
      <li><a href="{{route('adm.usuarios')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-users mr-2"></i>Usuarios</a></li>
      <li><a href="{{route('adm.metadatos')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-cog mr-2"></i>Metadatos</a></li>
    </ul>
  </div>

</aside>


  <script>
  function toggleSubMenu(button) {
    const li = button.parentElement;
    const submenu = li.querySelector('.submenu');
    submenu.classList.toggle('hidden');
    li.classList.toggle('open');
  }
</script>