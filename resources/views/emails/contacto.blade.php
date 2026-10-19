<h2>Nuevo mensaje de contacto</h2>
<p><strong>Nombre:</strong> {{ $data['nombre'] ?? '' }}</p>
<p><strong>Apellido:</strong> {{ $data['apellido'] ?? '' }}</p>
<p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>
<p><strong>Teléfono:</strong> {{ $data['telefono'] ?? '' }}</p>
<p><strong>Razón social:</strong> {{ $data['razon_social'] ?? '' }}</p>
<p><strong>CUIT-CUIL:</strong> {{ $data['cuit'] ?? '' }}</p>
<p><strong>Motivo:</strong> {{ $data['motivo'] ?? '' }}</p>

@php
    $items = collect($data['productos'] ?? [])->filter(fn ($i) => !empty($i['producto_id']));
    $nombresProductos = \App\Models\Producto::whereIn('id', $items->pluck('producto_id'))->pluck('nombre', 'id');
@endphp
@if($items->isNotEmpty())
    <p><strong>Productos:</strong></p>
    <ul>
        @foreach($items as $item)
            <li>
                {{ $nombresProductos[$item['producto_id']] ?? 'Producto #'.$item['producto_id'] }}
                @if(!empty($item['seccion'])) — Sección: {{ $item['seccion'] }} @endif
                — Cantidad: {{ $item['cantidad'] ?? 0 }}
            </li>
        @endforeach
    </ul>
@endif

<p><strong>Comentario:</strong></p>
<p>{{ $data['mensaje'] ?? '' }}</p>
