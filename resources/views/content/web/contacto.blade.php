@php
    $contacto_contenido = App\Models\Contacto::first();
    $productos_lista = App\Models\Producto::where('active', true)->orderBy('nombre')->get(['id', 'nombre', 'foto', 'caracteristicas_tecnicas']);
    // Secciones de cada producto (columna "sección" de sus características técnicas)
    $secciones_por_producto = $productos_lista->mapWithKeys(fn ($p) => [$p->id => $p->secciones])->filter();
    $motivos = App\Models\Consulta::where('active', true)->orderBy('orden')->pluck('nombre');

    // Si se llega desde "Consultar" de un producto, el mensaje viene armado con su nombre
    $producto_consulta = request()->filled('producto')
        ? App\Models\Producto::where('active', true)->find(request('producto'))
        : null;
    $mensaje_inicial = $producto_consulta
        ? 'Hola, quisiera consultar por el producto "' . $producto_consulta->nombre . '". Quedo a la espera de su respuesta. ¡Gracias!'
        : '';

    $inputClass = 'w-full h-[40px] bg-white border border-[#DEDFE0] px-[16px] text-[14px] text-[#303030] placeholder:text-[#303030]/50 focus:outline-none focus:border-[#086AFF]';
    $selectClass = $inputClass . ' appearance-none pr-[40px] bg-no-repeat bg-[right_12px_center]';
    $labelClass = 'text-[16px] leading-[1.5] text-[#303030]';
    $chevron = "background-image:url('" . asset('assets/img/contacto/chevron-down.svg') . "')";
@endphp

@extends('layouts.web')

@section('title', 'Contacto')

@section('content')

<div class="letra bg-[#F2F5F8] text-[#303030]">
<div class="max-w-[1366px] mx-auto px-[5%] lg:px-[71px] pt-[24px] pb-[80px]">

    {{-- breadcrumb --}}
    <p class="text-[12px] leading-[1.5]"><a href="{{ url('/') }}" class="font-bold">Inicio</a> &gt;&nbsp; Contacto</p>

    {{-- encabezado --}}
    <div class="mt-[70px]">
        <p class="text-[16px] leading-[1.5] text-[#086AFF]">CONTACTO</p>
        <h1 class="mt-[1px] text-[32px] lg:text-[40px] leading-[1.2] font-bold text-[#003879]">¿En qué te podemos ayudar?</h1>
        <p class="mt-[12px] text-[16px] leading-[1.5]">Elegí el canal que mejor se ajuste a tu consulta, o escribinos directamente por el formulario.</p>
    </div>

    {{-- canales --}}
    <div class="mt-[34px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px]">
        @if($contacto_contenido?->direccion)
        <a href="https://maps.google.com/?q={{ urlencode($contacto_contenido->direccion) }}" target="_blank" rel="noopener noreferrer" class="bg-white min-h-[180px] p-[24px] flex flex-col justify-start gap-[36px]">
            <img src="{{ asset('assets/img/contacto/map-pin.svg') }}" alt="" class="w-[34px] h-[34px]">
            <span class="text-[16px] leading-[1.5]">{{ $contacto_contenido->direccion }}</span>
        </a>
        @endif

        @if($contacto_contenido?->mail)
        <a href="mailto:{{ $contacto_contenido->mail }}" class="bg-white min-h-[180px] p-[24px] flex flex-col justify-start gap-[36px]">
            <img src="{{ asset('assets/img/contacto/mail.svg') }}" alt="" class="w-[34px] h-[34px]">
            <span class="text-[16px] leading-[1.5] break-all">{{ $contacto_contenido->mail }}</span>
        </a>
        @endif

        @if($contacto_contenido?->telefono)
        <a href="tel:{{ preg_replace('/[^\d+]/', '', $contacto_contenido->telefono) }}" class="bg-white min-h-[180px] p-[24px] flex flex-col justify-start gap-[36px]">
            <img src="{{ asset('assets/img/contacto/phone.svg') }}" alt="" class="w-[34px] h-[34px]">
            <span class="text-[16px] leading-[1.5]">{{ $contacto_contenido->telefono }}</span>
        </a>
        @endif

        @if($contacto_contenido?->whatssap)
        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $contacto_contenido->whatssap) }}" target="_blank" rel="noopener noreferrer" class="bg-white min-h-[180px] p-[24px] flex flex-col justify-start gap-[36px]">
            <img src="{{ asset('assets/img/contacto/whatsapp.svg') }}" alt="" class="w-[30px] h-[30px]">
            <span class="text-[16px] leading-[1.5]">{{ $contacto_contenido->whatssap }}</span>
        </a>
        @endif
    </div>

    <form id="formContacto" action="{{ route('form.contacto') }}" method="POST">
        @csrf
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

        {{-- productos --}}
        <div id="productosWrap" class="mt-[24px] flex flex-col gap-[2px]"></div>
        <button type="button" id="agregarProducto" class="mt-[16px] text-[16px] leading-[1.5] font-bold text-[#003879]">+Agregar más productos</button>

        {{-- mapa + formulario --}}
        <div class="mt-[32px] grid grid-cols-1 lg:grid-cols-2 gap-0">
            <div class="min-h-[400px] lg:min-h-0 bg-white">
                <div id="mapaContacto" class="relative z-0 w-full h-full min-h-[400px]"></div>
            </div>

            <div class="bg-white p-[24px] flex flex-col gap-[20px]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[20px]">
                    <div class="flex flex-col gap-[4px]">
                        <label for="nombre" class="{{ $labelClass }}">Nombre*</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Juan" required class="{{ $inputClass }}">
                    </div>
                    <div class="flex flex-col gap-[4px]">
                        <label for="apellido" class="{{ $labelClass }}">Apellido*</label>
                        <input type="text" id="apellido" name="apellido" placeholder="Perez" required class="{{ $inputClass }}">
                    </div>
                    <div class="flex flex-col gap-[4px]">
                        <label for="telefono" class="{{ $labelClass }}">Teléfono*</label>
                        <input type="tel" id="telefono" name="telefono" inputmode="numeric" placeholder="Código de área + 12345678" required class="{{ $inputClass }}">
                    </div>
                    <div class="flex flex-col gap-[4px]">
                        <label for="email" class="{{ $labelClass }}">Email*</label>
                        <input type="email" id="email" name="email" placeholder="juanperez@mail.com" required class="{{ $inputClass }}">
                    </div>
                    <div class="flex flex-col gap-[4px]">
                        <label for="razon_social" class="{{ $labelClass }}">Razón social*</label>
                        <input type="text" id="razon_social" name="razon_social" placeholder="-" required class="{{ $inputClass }}">
                    </div>
                    <div class="flex flex-col gap-[4px]">
                        <label for="cuit" class="{{ $labelClass }}">CUIT-CUIL*</label>
                        <input type="text" id="cuit" name="cuit" inputmode="numeric" placeholder="2712345678" required class="{{ $inputClass }}">
                    </div>
                </div>

                <div class="flex flex-col gap-[4px]">
                    <label for="motivo" class="{{ $labelClass }}">Motivo de la consulta</label>
                    <select id="motivo" name="motivo" class="{{ $selectClass }}" style="{{ $chevron }}">
                        <option value="">Seleccionar motivo</option>
                        @foreach($motivos as $motivo)
                            <option value="{{ $motivo }}">{{ $motivo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-[4px]">
                    <label for="mensaje" class="{{ $labelClass }}">Comentario</label>
                    <textarea id="mensaje" name="mensaje" rows="6" placeholder="Dejanos un comentario" required
                        class="w-full h-[160px] bg-white border border-[#DEDFE0] px-[16px] py-[12px] text-[14px] text-[#303030] placeholder:text-[#303030]/50 focus:outline-none focus:border-[#086AFF] resize-none">{{ $mensaje_inicial }}</textarea>
                </div>

                <div class="mt-[8px] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-[16px]">
                    <p class="text-[16px] leading-[1.5]">*Campos obligatorios</p>
                    <button type="submit" class="w-full sm:w-[288px] bg-[#086AFF] text-white text-[16px] leading-[1.5] p-[10px]">ENVIAR</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>

{{-- plantilla de fila de producto --}}
<template id="productoRowTpl">
    <div class="producto-row bg-white min-h-[108px] flex flex-col lg:flex-row lg:items-center gap-[16px] lg:gap-[40px] pr-[16px] lg:pr-[24px]">
        <div class="w-full lg:w-[104px] h-[108px] bg-[#F2F5F8] shrink-0 overflow-hidden">
            <img src="" alt="" class="producto-foto w-full h-full object-cover hidden">
        </div>

        <div class="flex flex-col gap-[4px] w-full lg:w-[352px]">
            <label class="{{ $labelClass }}">Producto</label>
            <select data-field="producto_id" class="producto-select {{ $selectClass }} border-[#EDE9E2]" style="{{ $chevron }}">
                <option value="">Seleccionar producto</option>
                @foreach($productos_lista as $p)
                    <option value="{{ $p->id }}" data-foto="{{ $p->foto }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-[4px] w-full lg:w-[352px]">
            <label class="{{ $labelClass }}">Sección</label>
            <select data-field="seccion" disabled class="seccion-select {{ $selectClass }} border-[#EDE9E2] disabled:opacity-50 disabled:cursor-not-allowed" style="{{ $chevron }}">
                <option value="">Seleccionar sección</option>
            </select>
        </div>

        <div class="flex flex-col gap-[4px]">
            <label class="{{ $labelClass }}">Cantidad</label>
            <div class="flex items-center justify-between w-[108px] h-[40px] bg-white border border-[#EDE9E2] px-[11px]">
                <button type="button" class="cant-menos" aria-label="Menos"><img src="{{ asset('assets/img/contacto/minus.svg') }}" alt="" class="w-[16px] h-[16px]"></button>
                <input type="number" data-field="cantidad" value="0" min="0" class="cant-input w-[40px] text-center text-[20px] leading-[1.5] text-[#303030]/50 bg-transparent focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                <button type="button" class="cant-mas" aria-label="Más"><img src="{{ asset('assets/img/contacto/plus.svg') }}" alt="" class="w-[16px] h-[16px]"></button>
            </div>
        </div>

        <button type="button" class="quitar-producto lg:ml-auto self-end lg:self-center" aria-label="Quitar producto">
            <img src="{{ asset('assets/img/contacto/trash.svg') }}" alt="" class="w-[19.5px] h-[24px]">
        </button>
    </div>
</template>

<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const formContacto = document.getElementById('formContacto');
    const recaptchaSiteKey = "{{ env('RECAPTCHA_SITE_KEY') }}";
    const wrap = document.getElementById('productosWrap');
    const tpl = document.getElementById('productoRowTpl');
    const seccionesPorProducto = @json($secciones_por_producto->toArray());

    // Solo números en teléfono y CUIT
    ['telefono', 'cuit'].forEach(id => {
        document.getElementById(id).addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // ---- filas de producto ----
    function reindex() {
        wrap.querySelectorAll('.producto-row').forEach((row, i) => {
            row.querySelectorAll('[data-field]').forEach(el => {
                el.name = `productos[${i}][${el.dataset.field}]`;
            });
        });
    }

    function addRow() {
        const row = tpl.content.firstElementChild.cloneNode(true);
        const select = row.querySelector('.producto-select');
        const seccion = row.querySelector('.seccion-select');
        const foto = row.querySelector('.producto-foto');
        const cant = row.querySelector('.cant-input');

        // las secciones dependen del producto elegido
        function cargarSecciones() {
            const lista = seccionesPorProducto[select.value] || [];
            const vacio = !select.value ? 'Seleccionar sección' : (lista.length ? 'Seleccionar sección' : 'Sin secciones');
            seccion.replaceChildren(new Option(vacio, ''), ...lista.map(v => new Option(v, v)));
            seccion.disabled = lista.length === 0;
        }

        select.addEventListener('change', () => {
            const src = select.selectedOptions[0]?.dataset.foto;
            if (src) { foto.src = src; foto.classList.remove('hidden'); }
            else { foto.removeAttribute('src'); foto.classList.add('hidden'); }
            cargarSecciones();
        });
        row.querySelector('.cant-mas').addEventListener('click', () => { cant.value = (parseInt(cant.value) || 0) + 1; });
        row.querySelector('.cant-menos').addEventListener('click', () => { cant.value = Math.max(0, (parseInt(cant.value) || 0) - 1); });
        row.querySelector('.quitar-producto').addEventListener('click', () => {
            if (wrap.children.length > 1) { row.remove(); }
            else { select.value = ''; select.dispatchEvent(new Event('change')); cant.value = 0; }
            reindex();
        });

        wrap.appendChild(row);
        reindex();
    }

    addRow();
    document.getElementById('agregarProducto').addEventListener('click', addRow);

    // ---- envío ----
    formContacto.addEventListener('submit', function (e) {
        e.preventDefault();

        // descartar filas sin producto elegido
        wrap.querySelectorAll('.producto-row').forEach(row => {
            if (!row.querySelector('.producto-select').value) {
                row.querySelectorAll('[data-field]').forEach(el => el.removeAttribute('name'));
            }
        });

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';

        grecaptcha.ready(function () {
            grecaptcha.execute(recaptchaSiteKey, { action: 'contacto' }).then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
                const formData = new FormData(formContacto);

                fetch(formContacto.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Mensaje enviado!',
                            text: data.message || 'Tu consulta ha sido enviada exitosamente.',
                            confirmButtonColor: '#086AFF'
                        });
                        formContacto.reset();
                        document.getElementById('mensaje').value = ''; // reset() volvería al mensaje predefinido
                        wrap.innerHTML = '';
                        addRow();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Hubo un problema al enviar tu consulta.',
                            confirmButtonColor: '#003879'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error. Por favor, intenta nuevamente.',
                        confirmButtonColor: '#003879'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    reindex();
                });
            });
        });
    });
});
</script>

{{-- Mapa: Leaflet + OpenStreetMap (gratuito, sin API key) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = parseFloat("{{ $contacto_contenido->latitud ?? '' }}");
    const lng = parseFloat("{{ $contacto_contenido->longitud ?? '' }}");
    const contenedor = document.getElementById('mapaContacto');

    if (!contenedor || isNaN(lat) || isNaN(lng)) return;

    // ---- Personalización ----
    // Colores del mapa: filtro CSS aplicado a las baldosas. Ejemplos:
    //   gris claro:  'grayscale(1) contrast(.9) brightness(1.1)'
    //   azulado:     'grayscale(1) sepia(.35) hue-rotate(170deg) saturate(1.6) brightness(1.05)'
    //   color normal: 'none'
    const FILTRO_MAPA = 'grayscale(1) contrast(.9) brightness(1.1)';
    const ZOOM = 15;

    // Ícono del pin (SVG propio, tamaño y punto de anclaje configurables)
    const iconoPin = L.icon({
        iconUrl: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" viewBox="0 0 50 50" fill="none">
            <path d="M41.6666 20.8334C41.6666 33.3334 24.9999 45.8334 24.9999 45.8334C24.9999 45.8334 8.33325 33.3334 8.33325 20.8334C8.33325 16.4131 10.0892 12.1738 13.2148 9.04824C16.3404 5.92263 20.5796 4.16669 24.9999 4.16669C29.4202 4.16669 33.6594 5.92263 36.785 9.04824C39.9106 12.1738 41.6666 16.4131 41.6666 20.8334Z" fill="#086AFF" stroke="#086AFF" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M25 27.0833C28.4518 27.0833 31.25 24.2851 31.25 20.8333C31.25 17.3815 28.4518 14.5833 25 14.5833C21.5482 14.5833 18.75 17.3815 18.75 20.8333C18.75 24.2851 21.5482 27.0833 25 27.0833Z" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        `),
        iconSize: [48, 48],
        iconAnchor: [24, 48]
    });

    const mapa = L.map(contenedor, { zoomControl: true, attributionControl: true }).setView([lat, lng], ZOOM);
    mapa.attributionControl.setPrefix(false);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
    }).addTo(mapa);

    // Aplica los colores solo a las baldosas (el pin y los controles quedan intactos)
    mapa.getPane('tilePane').style.filter = FILTRO_MAPA;

    L.marker([lat, lng], { icon: iconoPin }).addTo(mapa);
});
</script>

@endsection
