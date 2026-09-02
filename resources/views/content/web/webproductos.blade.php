@extends('layouts.layout-clientes')

@section('title', 'Productos')

@section('content')
@php
    $marcas = $marcas ?? App\Models\Marca::where('active', true)->orderBy('orden')->get();
    $descuentos = collect($descuentos ?? [])
        ->map(fn ($d) => (float) $d)
        ->filter(fn ($d) => $d > 0)
        ->values()
        ->all();

    $factorDescuento = 1;
    foreach ($descuentos as $descuento) {
        $factorDescuento *= (1 - ($descuento / 100));
    }

    $descuentosTexto = collect($descuentos)
        ->map(fn ($d) => rtrim(rtrim(number_format($d, 2, ',', '.'), '0'), ',') . '%')
        ->implode(' + ');
@endphp

<div class="pt-[10px] pb-[180px] h-full letra max-w-[1366px] mx-auto px-[5%] md:px-[71px]">
    <div class="flex flex-col w-full pb-[13px]">
        <h3 class="text-[12px] mb-[26px] pt-[26px] text-[#3A3A3A]">
            <a class="font-bold" href="{{ route('home') }}">Inicio</a> > Productos
        </h3>
    </div>

    <x-product-search :action="route('web-productos')" :marcas="$marcas" />

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-[#A60000] text-white font-semibold text-[16px] h-[52px]">
                    <th class="px-3 text-left rounded-tl-[14px] w-[70px]"></th>
                    <th class="px-3 text-left min-w-[100px]">Cod.</th>
                    <th class="px-3 text-left min-w-[200px]">Producto</th>
                    <th class="px-3 text-right min-w-[120px]">Precio</th>
                    <th class="px-3 text-center min-w-[160px]">Precio con desc.</th>
                    <th class="px-3 text-center min-w-[100px]">Cantidad</th>
                    <th class="px-3 rounded-tr-[14px] min-w-[50px]"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5] text-[14px]" id="tbody-productos">
                @forelse($productos as $producto)
                    @php
                        $precio = (float) ($producto->precio ?? 0);
                        $cantidadInicial = 1;
                        $precioConDesc = $precio * $factorDescuento;
                    @endphp
                    <tr class="bg-white hover:bg-gray-50" data-product-id="{{ $producto->id }}">
                        <td class="p-3">
                            @if(!empty($producto->foto))
                                <img src="{{ asset($producto->foto) }}" alt="{{ $producto->nombre }}" class="w-[50px] h-[50px] object-cover rounded">
                            @else
                                <div class="w-[50px] h-[50px] bg-[#F5F5F5] rounded flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#CCCCCC" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        <td class="p-3 font-bold">{{ $producto->codigo }}</td>

                        <td>
                            <div class="text-[13px] leading-tight max-w-[350px]">{{ $producto->nombre }}</div>
                        </td>

                        <td class="p-3 text-right">
                            <span class="text-[14px] font-medium">$ {{ number_format($precio, 2, ',', '.') }}</span>
                        </td>

                        <td class="p-3 text-center">
                            <div class="flex flex-col items-center gap-[2px]">
                                <span class="precio-con-desc text-[14px] font-medium text-[#1A1A1A]" data-row="{{ $producto->id }}">
                                    $ {{ number_format($precioConDesc * $cantidadInicial, 2, ',', '.') }}
                                </span>
                                @if(!empty($descuentosTexto))
                                    <span class="text-[13px] font-medium text-[#308C05]">
                                        ({{ $descuentosTexto }})
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="p-3 text-center">
                            <input type="number" value="{{ $cantidadInicial }}" min="0" step="1"
                                class="cantidad w-[80px] h-[34px] border border-[#D9D9D9] rounded text-center text-[13px]"
                                data-row="{{ $producto->id }}"
                                data-precio="{{ $precio }}"
                                data-precio-con-desc="{{ $precioConDesc }}"
                                data-tiene-desc-compuesto="{{ !empty($descuentos) ? 'true' : 'false' }}">
                        </td>

                        <td class="p-3 text-center">
                            <button class="add-to-cart-btn flex items-center justify-center w-[36px] h-[36px] border border-[#DE2222] text-[#DE2222] rounded hover:bg-[#DE2222] hover:text-white transition mx-auto"
                                data-codigo="{{ $producto->codigo }}" data-descripcion="{{ \Illuminate\Support\Str::limit($producto->nombre, 200) }}" data-precio-fracc="{{ $precio }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                                    <path d="M4.50416 16C4.09128 16 3.73795 15.8435 3.44418 15.5304C3.15041 15.2173 3.00327 14.8405 3.00277 14.4C3.00277 13.96 3.14991 13.5835 3.44418 13.2704C3.73845 12.9573 4.09178 12.8005 4.50416 12.8C4.91704 12.8 5.27062 12.9568 5.56489 13.2704C5.85916 13.584 6.00605 13.9605 6.00555 14.4C6.00555 14.84 5.85866 15.2168 5.56489 15.5304C5.27112 15.844 4.91754 16.0005 4.50416 16ZM12.0111 16C11.5982 16 11.2449 15.8435 10.9511 15.5304C10.6573 15.2173 10.5102 14.8405 10.5097 14.4C10.5097 13.96 10.6568 13.5835 10.9511 13.2704C11.2454 12.9573 11.5987 12.8005 12.0111 12.8C12.424 12.8 12.7776 12.9568 13.0718 13.2704C13.3661 13.584 13.513 13.9605 13.5125 14.4C13.5125 14.84 13.3656 15.2168 13.0718 15.5304C12.7781 15.844 12.4245 16.0005 12.0111 16ZM3.86607 3.2L5.66774 7.2H10.9226L12.987 3.2H3.86607ZM3.15291 1.6H14.2256C14.5134 1.6 14.7324 1.7368 14.8825 2.0104C15.0326 2.284 15.0389 2.56053 14.9013 2.84L12.2363 7.96C12.0987 8.22667 11.9143 8.43333 11.683 8.58C11.4518 8.72667 11.1983 8.8 10.9226 8.8H5.32992L4.50416 10.4H13.5125V12H4.50416C3.94114 12 3.51575 11.7368 3.22798 11.2104C2.94022 10.684 2.9277 10.1605 3.19045 9.64L4.20388 7.68L1.50139 1.6H0V0H2.43975L3.15291 1.6Z" fill="currentColor"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-600 bg-white">
                            No se encontraron productos con esos filtros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-[40px]">
        {{ $productos->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatPesos(n) {
            return '$ ' + Number(n).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function actualizarPrecioConDescuento(rowId) {
            const input = document.querySelector(`.cantidad[data-row="${rowId}"]`);
            const precioEl = document.querySelector(`.precio-con-desc[data-row="${rowId}"]`);
            if (!input || !precioEl) return;

            let cantidad = parseInt(input.value || 0, 10);
            if (cantidad < 0) cantidad = 0;
            input.value = cantidad;

            const precioConDesc = parseFloat(input.dataset.precioConDesc || 0);
            precioEl.textContent = formatPesos(precioConDesc * cantidad);
        }

        document.querySelectorAll('.cantidad').forEach(input => {
            const rowId = input.dataset.row;
            actualizarPrecioConDescuento(rowId);
            input.addEventListener('change', () => actualizarPrecioConDescuento(rowId));
            input.addEventListener('input', () => actualizarPrecioConDescuento(rowId));
        });

        function agregarAlCarrito(btn, cantidad) {
            const row = btn.closest('tr');
            const inputCantidad = row?.querySelector('.cantidad');
            const productoId = inputCantidad?.dataset.row;
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

            if (!productoId) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo identificar el producto.', confirmButtonColor: '#DE2222' });
                return;
            }

            fetch('{{ route('web-carrito.agregar') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ producto_id: productoId, cantidad })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto agregado',
                        text: 'El producto se agrego al carrito.',
                        showConfirmButton: false,
                        timer: 1400
                    });
                    if (inputCantidad) {
                        inputCantidad.value = 0;
                        actualizarPrecioConDescuento(inputCantidad.dataset.row);
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al agregar al carrito', confirmButtonColor: '#DE2222' });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error de red', text: 'No se pudo agregar el producto.', confirmButtonColor: '#DE2222' });
            });
        }

        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const row = this.closest('tr');
                const inputCantidad = row?.querySelector('.cantidad');
                const cantidad = parseInt(inputCantidad?.value || 0, 10);

                if (cantidad <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad requerida',
                        text: 'Por favor, ingresa una cantidad antes de agregar al carrito.',
                        confirmButtonColor: '#DE2222'
                    });
                    return;
                }

                agregarAlCarrito(this, cantidad);
            });
        });
    });
</script>
@endsection
