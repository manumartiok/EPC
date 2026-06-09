<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido web</title>
</head>
<body style="font-family: Arial, sans-serif; color: #242424; line-height: 1.45;">
    <h2 style="margin-bottom: 8px;">
        @if($destinatarioTipo === 'duenos')
            Nuevo pedido web recibido
        @else
            Confirmacion de pedido
        @endif
    </h2>

    <p style="margin-top: 0;">
        @if($destinatarioTipo === 'duenos')
            Se recibio un nuevo pedido desde el sitio.
        @else
            Recibimos tu pedido correctamente. Te contactaremos para coordinar los detalles.
        @endif
    </p>

    <h3>Cliente</h3>
    <p>
        <strong>Usuario:</strong> {{ $pedido['cliente']['usuario'] ?? '-' }}<br>
        <strong>Email:</strong> {{ $pedido['cliente']['email'] ?? '-' }}<br>
        <strong>Empresa:</strong> {{ $pedido['cliente']['empresa'] ?? '-' }}<br>
        <strong>CUIT:</strong> {{ $pedido['cliente']['cuit'] ?? '-' }}<br>
        <strong>Telefono:</strong> {{ $pedido['cliente']['telefono'] ?? '-' }}
    </p>

    <h3>Datos del pedido</h3>
    <p>
        <strong>Entrega:</strong> {{ $pedido['entrega_label'] ?? '-' }}<br>
        <strong>Forma de pago:</strong> {{ $pedido['forma_pago_label'] ?? '-' }}<br>
        <strong>Mensaje:</strong> {{ $pedido['mensaje'] ?: '-' }}
    </p>

    <h3>Productos</h3>
    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; border-color: #dddddd;">
        <thead>
            <tr style="background: #A60000; color: #ffffff;">
                <th align="left">Codigo</th>
                <th align="left">Producto</th>
                <th align="right">Precio</th>
                <th align="right">Precio con desc.</th>
                <th align="right">Cantidad</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido['items'] as $item)
                <tr>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['nombre'] }}</td>
                    <td align="right">$ {{ number_format($item['precio_base'], 2, ',', '.') }}</td>
                    <td align="right">$ {{ number_format($item['precio_con_descuento'], 2, ',', '.') }}</td>
                    <td align="right">{{ $item['cantidad'] }}</td>
                    <td align="right">$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Resumen</h3>
    <p>
        <strong>Subtotal sin descuento:</strong> $ {{ number_format($pedido['totales']['subtotal_sin_descuento'], 2, ',', '.') }}<br>
        <strong>Descuentos cliente:</strong> $ {{ number_format($pedido['totales']['descuento_items'], 2, ',', '.') }}<br>
        @if(($pedido['totales']['descuento_forma_pago'] ?? 0) > 0)
            <strong>Descuento por forma de pago:</strong> $ {{ number_format($pedido['totales']['descuento_forma_pago'], 2, ',', '.') }}<br>
        @endif
        <strong>Subtotal:</strong> $ {{ number_format($pedido['totales']['subtotal'], 2, ',', '.') }}<br>
        <strong>IVA 21%:</strong> $ {{ number_format($pedido['totales']['iva'], 2, ',', '.') }}<br>
        <strong>Total:</strong> $ {{ number_format($pedido['totales']['total'], 2, ',', '.') }}
    </p>
</body>
</html>
