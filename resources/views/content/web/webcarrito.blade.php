@extends('layouts.layout-clientes')

@section('title', 'Carrito')

@section('content')
@php
    $descuentos = collect($descuentos ?? [])
        ->map(fn ($d) => (float) $d)
        ->filter(fn ($d) => $d > 0)
        ->values()
        ->all();

    $factorDescuento = 1;
    foreach ($descuentos as $descuento) {
        $factorDescuento *= (1 - ($descuento / 100));
    }

    $descuentoCliente = round((1 - $factorDescuento) * 100, 2);
    $descuentosTexto = collect($descuentos)
        ->map(fn ($d) => rtrim(rtrim(number_format($d, 2, ',', '.'), '0'), ',') . '%')
        ->implode(' + ');
    $descuentoFormaPago = 5;
    $productosCarrito = $productosCarrito ?? [];
@endphp
<div class="pt-[10px] pb-[180px] h-full jakarta max-w-[1366px] mx-auto px-[5%] md:px-[71px]">

    <!-- Breadcrumb -->
    <h3 class="text-[12px] mb-[40px] pt-[26px] text-[#3A3A3A]">
        <a class="font-bold" href="{{ route('home') }}">Inicio</a> > Carrito
    </h3>

    <!-- Tabla de productos (versión simplificada - solo fraccionado) -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-[#A60000] text-white font-semibold text-[16px] h-[52px] rounded-[10px]">
                    <th class="px-3 text-left rounded-tl-[14px] w-[70px]"></th>
                    <th class="px-3 text-left  w-[80px]">Cód</th>
                    <th class="px-3 text-left min-w-[300px]">Producto</th>
                    <th class="px-3 text-right min-w-[120px]">Precio</th>
                    <th class="px-3 text-center min-w-[80px]">Desc.</th>
                    <th class="px-3 text-center min-w-[130px]">Precio con desc.</th>
                    <th class="px-3 text-center min-w-[100px]">Cantidad</th>
                    <th class="px-3 text-center min-w-[50px] rounded-tr-[14px]"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5] text-[14px]" id="tbody-carrito">
                @forelse($productosCarrito as $row)
                    @php
                        $producto = $row['producto'];
                        $cantidad = (int) ($row['cantidad'] ?? 1);
                        $precio = (float) ($row['precio_base'] ?? $producto->precio);
                        $precioConDescItem = $precio * $factorDescuento;
                    @endphp
                    <tr class="bg-white text-[#3A3A3A] hover:bg-gray-50" data-item-key="{{ $producto->id }}">
                        <td class="p-3">
                            @if(!empty($producto->foto))
                                <img src="{{ $producto->foto }}" alt="{{ $producto->nombre ?? '' }}" class="w-[50px] h-[50px] object-cover rounded">
                            @else
                                <div class="w-[50px] h-[50px] bg-gray-100 rounded"></div>
                            @endif
                        </td>
                        <td class="p-3 font-bold">{{ $producto->codigo ?? '-' }}</td>
                        <td class="p-3">{{ $producto->nombre ?? '' }}</td>
                        <td class="p-3 text-right">$ {{ number_format($precio, 2, ',', '.') }}</td>
                        <td class="p-3 text-center text-[#308C05] font-medium">{{ $descuentosTexto ?: '0%' }}</td>
                        <td class="p-3 text-center">
                            <span class="precio-con-desc-carrito" data-key="{{ $producto->id }}">
                                $ {{ number_format($precioConDescItem * $cantidad, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <input type="number" value="{{ $cantidad }}" min="1" 
                                class="w-[70px] text-center border border-[#E5E5E5] rounded px-2 py-1 cant-item" 
                                data-key="{{ $producto->id }}" 
                                data-precio-original="{{ $precio }}"
                                data-precio-con-desc="{{ $precioConDescItem }}">
                        </td>
                        <td class="p-3 text-center">
                            <button class="btn-eliminar flex justify-center items-center h-[40px] w-[48px] border border-[#DE2222] rounded-[8px]" data-key="{{ $producto->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M6.28531 8.571L7.42831 20H16.5703L17.7133 8.571M13.4993 15.5V10.5M10.4993 15.5V10.5M4.57031 6.286H9.14231M9.14231 6.286L9.52431 4.757C9.57848 4.54075 9.70336 4.34881 9.8791 4.21166C10.0548 4.0745 10.2714 4.00001 10.4943 4H13.5043C13.7272 4.00001 13.9438 4.0745 14.1195 4.21166C14.2953 4.34881 14.4201 4.54075 14.4743 4.757L14.8563 6.286M9.14231 6.286H14.8563M14.8563 6.286H19.4283" stroke="#DE2222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-8 text-gray-500">El carrito está vacío.</td></tr>
                @endforelse
            </tbody>
         </table>
    </div>

    <!-- Botón agregar más productos -->
    <div class="mt-8 flex justify-start">
        <a href="{{ route('web-productos') }}" class="flex justify-center items-center text-[14px] font-bold text-[#DE2222] border border-[#DE2222] rounded-[14px] w-full max-w-[297px] h-[46px] transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M5.588 21.413C5.196 21.021 5 20.55 5 20C5 19.45 5.196 18.9793 5.588 18.588C5.98 18.1967 6.45067 18.0007 7 18C7.54933 17.9993 8.02033 18.1953 8.413 18.588C8.80567 18.9807 9.00133 19.4513 9 20C8.99867 20.5487 8.803 21.0197 8.413 21.413C8.023 21.8063 7.552 22.002 7 22C6.448 21.998 5.97733 21.8023 5.588 21.413ZM15.588 21.413C15.196 21.021 15 20.55 15 20C15 19.45 15.196 18.9793 15.588 18.588C15.98 18.1967 16.4507 18.0007 17 18C17.5493 17.9993 18.0203 18.1953 18.413 18.588C18.8057 18.9807 19.0013 19.4513 19 20C18.9987 20.5487 18.803 21.0197 18.413 21.413C18.023 21.8063 17.552 22.002 17 22C16.448 21.998 15.9773 21.8023 15.588 21.413ZM6.15 6L8.55 11H15.55L18.3 6H6.15ZM5.2 4H19.95C20.3333 4 20.625 4.171 20.825 4.513C21.025 4.855 21.0333 5.20067 20.85 5.55L17.3 11.95C17.1167 12.2833 16.871 12.5417 16.563 12.725C16.255 12.9083 15.9173 13 15.55 13H8.1L7 15H19V17H7C6.25 17 5.68333 16.671 5.3 16.013C4.91667 15.355 4.9 14.7007 5.25 14.05L6.6 11.6L3 4H1V2H4.25L5.2 4Z" fill="#DE2222"/>
</svg> Agregar más productos
        </a>
    </div>

    <!-- Fila 1: Información importante + Entrega -->
    <div class="flex flex-col md:flex-row gap-[24px] mt-[60px]">
        <!-- Información importante -->
        <div class="w-full md:w-1/2 border border-[#DDDDE0] rounded-[6px]">
            <div class="h-[56px] bg-[#A60000] px-[24px] flex items-center text-[22px] text-white font-semibold rounded-t-[6px]">
                <h1>Información importante</h1>
            </div>
            <div class="text-[16px]  p-[20px]">
                <ul class="list-disc pl-5 space-y-2">
                    <li>Venta sujeta a disponibilidad en stock</li>
                    <li>Los precios se encuentran expresados en pesos</li>
                    <li>El plazo de entrega se coordina con la empresa</li>
                </ul>
            </div>
        </div>

        <!-- Entrega -->
        <div class="w-full md:w-1/2 border border-[#DDDDE0] rounded-[6px]">
            <div class="h-[56px] bg-[#A60000] px-[24px] flex items-center text-[22px] text-white font-semibold rounded-t-[6px]">
                <h1>Entrega</h1>
            </div>
            <div class="p-[20px] space-y-[12px] text-[16px] ">
                <label class="flex items-center gap-[10px] cursor-pointer">
                    <input type="radio" name="entrega" value="retiro" class="w-4 h-4 accent-[#A60000]"> Retiro cliente
                </label>
                <label class="flex items-center gap-[10px] cursor-pointer">
                    <input type="radio" name="entrega" value="reparto" class="w-4 h-4 accent-[#A60000]"> Reparto Resortes generales
                </label>
                <label class="flex items-center gap-[10px] cursor-pointer">
                    <input type="radio" name="entrega" value="convenir" class="w-4 h-4 accent-[#A60000]"> A Convenir
                </label>
            </div>
        </div>
    </div>

    <!-- Fila 2: Escribinos un mensaje + Forma de pago -->
    <div class="flex flex-col md:flex-row gap-[24px] mt-[24px]">
        <!-- Escribinos un mensaje -->
        <div class="w-full md:w-1/2">
            <div class="h-[56px] bg-[#A60000] px-[24px] flex items-center text-[22px] text-white font-semibold rounded-t-[6px]">
                <h1>Escribimos un mensaje</h1>
            </div>
            <textarea id="mensaje-pedido"
                class="w-full h-[180px] border border-[#DDDDE0] rounded-[6px] p-[12px] text-[14px]  resize-none focus:outline-none"
                placeholder="Días especiales de entrega, cambios de domicilio, expresos, requerimientos especiales en la mercadería, exenciones."></textarea>
        </div>

        <!-- Forma de pago -->
        <div class="w-full md:w-1/2">
            <div class="h-[56px] bg-[#A60000] px-[24px] flex items-center text-[22px] text-white font-semibold rounded-t-[6px]">
                <h1>Forma de pago</h1>
            </div>
            <div class="border border-[#DDDDE0] rounded-[6px] p-[20px] space-y-[12px] text-[16px] ">
                <label class="flex items-center justify-between w-full cursor-pointer">
    <div class="flex items-center gap-[10px]">
        <input type="radio" name="forma_pago" value="contado" class="w-4 h-4 accent-[#A60000]" checked>
        <span>Contado</span>
    </div>

    <span class="text-[#308C05] font-semibold text-[14px]">
        5% de descuento
    </span>
</label>
                <label class="flex items-center gap-[10px] cursor-pointer">
                    <input type="radio" name="forma_pago" value="cuenta_corriente" class="w-4 h-4 accent-[#A60000]"> Cuenta corriente
                </label>
            </div>
        </div>
    </div>

    <!-- Fila 3: Adjuntar archivo + Tu pedido -->
    <div class="flex flex-col md:flex-row gap-[24px] ">
        <!-- Adjuntar archivo -->
        <div class="w-full md:w-1/2">
            <div class="h-[56px] flex items-center text-[22px] font-semibold">
                <h1>Adjunta un archivo</h1>
            </div>
            <label class="flex items-center justify-between w-full border border-[#DDDDE0] rounded-md pl-3 h-[46px] cursor-pointer bg-transparent">
                <span class=" text-[14px]" id="file-name">Seleccionar archivo</span>
                <span class="bg-[#DE2222] h-full w-[135px] text-[14px] text-white font-bold flex justify-center items-center rounded-r-[6px]">Adjuntar</span>
                <input type="file" id="archivo-pedido" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Seleccionar archivo'">
            </label>
        </div>

        <!-- Tu pedido -->
        <div class="flex flex-col w-full md:w-1/2">
            <div class="w-full flex flex-col border border-[#DDDDE0] rounded-[6px]">
                <div class="h-[56px] bg-[#A60000] px-[24px] flex items-center text-[22px] text-white font-semibold rounded-t-[6px]">
                    <h1>Tu pedido</h1>
                </div>
                <div class="p-[20px] text-[16px] space-y-[10px]">
                    @php
                        // Calcular totales a partir de $productosCarrito
                        $subtotalSinDescuento = collect($productosCarrito)->sum(function ($it) {
                            $precio = (float) ($it['precio_base'] ?? ($it['producto']->precio ?? 0));
                            $cantidad = (int) ($it['cantidad'] ?? 1);
                            return $precio * $cantidad;
                        });

                        $subtotalConDescuentoItem = collect($productosCarrito)->sum(function ($it) use ($factorDescuento) {
                            $precio = (float) ($it['precio_base'] ?? ($it['producto']->precio ?? 0));
                            $precioDesc = $precio * $factorDescuento;
                            $cantidad = (int) ($it['cantidad'] ?? 1);
                            return $precioDesc * $cantidad;
                        });

                        $descuentoPorItems = $subtotalSinDescuento - $subtotalConDescuentoItem;

                        $descuentoFormaPagoMonto = $subtotalConDescuentoItem * ($descuentoFormaPago / 100);
                        $subtotalFinal = $subtotalConDescuentoItem - $descuentoFormaPagoMonto;

                        $iva = $subtotalFinal * 0.21;
                        $total = $subtotalFinal + $iva;
                    @endphp
                    <div class="flex justify-between">
                        <span>Subtotal sin descuento</span>
                        <span id="panel-subtotal-sin-desc">$ {{ number_format($subtotalSinDescuento, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#308C05]">
                        <span>Descuentos ({{ $descuentoCliente }}%)</span>
                        <span id="panel-descuento-items">$ {{ number_format($descuentoPorItems, 2, ',', '.') }}</span>
                    </div>
                    <div id="panel-descuento-pago-row" class="flex justify-between text-[#308C05]">
                        <span>Descuentos por forma de pago ({{ $descuentoFormaPago }}%)</span>
                        <span id="panel-descuento-pago">$ {{ number_format($descuentoFormaPagoMonto, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-[5px]">
                        <span>Subtotal</span>
                        <span id="panel-subtotal">$ {{ number_format($subtotalFinal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-[10px] border-t">
                        <span>IVA 21%</span>
                        <span id="panel-iva">$ {{ number_format($iva, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-[#242424] text-[18px] pt-[5px]">
                        <span>Total <span class="text-[12px] font-normal">(IVA incluido)</span></span>
                        <span id="panel-total">$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-[12px] pt-[24px]">
                <button id="btn-cancelar-pedido" 
                    class="w-1/2 border border-[#DE2222] text-[#DE2222] font-bold rounded-[6px] h-[46px] hover:bg-[#DE2222] hover:text-white transition">
                    Cancelar pedido
                </button>
                <button id="btn-realizar-pedido" 
                    class="w-1/2 bg-[#DE2222] text-white font-bold rounded-[6px] h-[46px] hover:bg-[#DE2222]/80 transition">
                    Realizar pedido
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const descuentoFormaPago = {{ $descuentoFormaPago }};
        const factorDescuento = {{ json_encode($factorDescuento) }};

        function formatPesos(n) {
            return '$ ' + Number(n).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        
        function actualizarSubtotales() {
            let subtotalSinDesc = 0;
            let subtotalConDescItem = 0;
            
            document.querySelectorAll('#tbody-carrito tr').forEach(row => {
                const input = row.querySelector('.cant-item');
                if (!input) return;

                let cantidad = parseInt(input.value || 1, 10);
                if (cantidad < 1) cantidad = 1;
                input.value = cantidad;

                const precio = parseFloat(input.dataset.precioOriginal || 0);
                const precioConDescItem = parseFloat(input.dataset.precioConDesc || (precio * factorDescuento));
                
                const subtotalItem = precioConDescItem * cantidad;
                const precioConDescEl = row.querySelector('.precio-con-desc-carrito');
                if (precioConDescEl) precioConDescEl.textContent = formatPesos(subtotalItem);
                
                subtotalSinDesc += precio * cantidad;
                subtotalConDescItem += subtotalItem;
            });
            
            const descuentoPorItems = subtotalSinDesc - subtotalConDescItem;
            const formaPago = document.querySelector('input[name="forma_pago"]:checked')?.value || '';
            const aplicaDescuentoFormaPago = formaPago === 'contado';
            const descuentoFormaPagoMonto = aplicaDescuentoFormaPago ? subtotalConDescItem * (descuentoFormaPago / 100) : 0;
            const subtotalFinal = subtotalConDescItem - descuentoFormaPagoMonto;
            const iva = subtotalFinal * 0.21;
            const total = subtotalFinal + iva;
            const descuentoPagoRow = document.getElementById('panel-descuento-pago-row');
            
            document.getElementById('panel-subtotal-sin-desc').textContent = formatPesos(subtotalSinDesc);
            document.getElementById('panel-descuento-items').textContent = formatPesos(descuentoPorItems);
            document.getElementById('panel-descuento-pago').textContent = formatPesos(descuentoFormaPagoMonto);
            document.getElementById('panel-subtotal').textContent = formatPesos(subtotalFinal);
            document.getElementById('panel-iva').textContent = formatPesos(iva);
            document.getElementById('panel-total').textContent = formatPesos(total);
            if (descuentoPagoRow) {
                descuentoPagoRow.classList.toggle('hidden', !aplicaDescuentoFormaPago);
            }
        }
        
        // Inicializar inputs desde atributos data- y sincronizar con backend cuando cambien
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

        document.querySelectorAll('.cant-item').forEach(input => {
            const precio = parseFloat(input.dataset.precioOriginal || 0);
            input.dataset.precioOriginal = precio;
            input.dataset.precioConDesc = parseFloat(input.dataset.precioConDesc || (precio * factorDescuento));

            input.addEventListener('change', function () {
                actualizarSubtotales();
                // enviar actualización al servidor
                const productoId = this.dataset.key;
                const cantidad = parseFloat(this.value || 0);
                fetch('{{ route('web-carrito.actualizar') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ producto_id: productoId, cantidad })
                }).catch(err => console.error(err));
            });

            input.addEventListener('input', actualizarSubtotales);
        });
        
        // Botón eliminar (llama al backend)
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.dataset.key;
                Swal.fire({
                    icon: 'warning',
                    title: 'Eliminar producto',
                    text: '¿Querés eliminar este producto del carrito?',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#DE2222'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    fetch('{{ route('web-carrito.eliminar') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify({ producto_id: key })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('tr').remove();
                            actualizarSubtotales();
                        }
                    })
                    .catch(err => console.error(err));
                });
            });
        });
        
        // Forma de pago (recalcular al cambiar)
        document.querySelectorAll('input[name="forma_pago"]').forEach(radio => {
            radio.addEventListener('change', actualizarSubtotales);
        });
        
        // Botón cancelar pedido
        document.getElementById('btn-cancelar-pedido')?.addEventListener('click', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Cancelar pedido',
                text: '¿Estás seguro de que deseas cancelar el pedido?',
                showCancelButton: true,
                confirmButtonText: 'Cancelar pedido',
                cancelButtonText: 'Volver',
                confirmButtonColor: '#DE2222'
            }).then(result => {
                if (!result.isConfirmed) return;
                document.querySelectorAll('#tbody-carrito tr').forEach(row => row.remove());
                actualizarSubtotales();
            });
        });
        
        // Botón realizar pedido
        document.getElementById('btn-realizar-pedido')?.addEventListener('click', function() {
    const entrega = document.querySelector('input[name="entrega"]:checked')?.value || '';
    const formaPago = document.querySelector('input[name="forma_pago"]:checked')?.value || '';

    if (!entrega) {
        Swal.fire({ icon: 'warning', title: 'Falta seleccionar entrega', text: 'Por favor elegí una opción de entrega.', confirmButtonColor: '#DE2222' });
        return;
    }
    if (!formaPago) {
        Swal.fire({ icon: 'warning', title: 'Falta forma de pago', text: 'Por favor elegí una forma de pago.', confirmButtonColor: '#DE2222' });
        return;
    }

    const mensaje = document.getElementById('mensaje-pedido')?.value || '';
    const archivo = document.getElementById('archivo-pedido')?.files[0] || null;

    const formData = new FormData();
    formData.append('entrega', entrega);
    formData.append('forma_pago', formaPago);
    formData.append('mensaje', mensaje);
    formData.append('_token', CSRF);
    if (archivo) formData.append('archivo', archivo);

    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    fetch('{{ route('web-carrito.completar') }}', {
        method: 'POST',
        body: formData   // NO pongas Content-Type manual; FormData lo maneja solo
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pedido realizado con éxito',
                text: 'Te enviamos la confirmación por email.',
                confirmButtonColor: '#DE2222'
            }).then(() => {
                // Limpiar tabla visualmente
                document.querySelectorAll('#tbody-carrito tr').forEach(row => row.remove());
                actualizarSubtotales();
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Ocurrió un error.', confirmButtonColor: '#DE2222' });
            btn.disabled = false;
            btn.textContent = 'Realizar pedido';
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#DE2222' });
        btn.disabled = false;
        btn.textContent = 'Realizar pedido';
    });
});

        actualizarSubtotales();
    });
</script>
@endsection
