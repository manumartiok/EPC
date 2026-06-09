<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Mail\PedidoConfirmacionMail;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PedidoController extends Controller
{
    private function descuentosDelUsuario($usuario): array
    {
        return collect(range(1, 5))
            ->map(fn ($i) => (float) data_get($usuario, "descuento{$i}", 0))
            ->filter(fn ($d) => $d > 0)
            ->values()
            ->all();
    }

    private function aplicarDescuentos(float $precio, array $descuentos): float
    {
        foreach ($descuentos as $descuento) {
            $precio *= (1 - ($descuento / 100));
        }

        return $precio;
    }

    // Mostrar vista del carrito
    public function index()
    {
        $carritoSesion = session('carrito', []);
        $productosCarrito = [];
        $usuario = auth('web_usuario')->user();
        $descuentos = $this->descuentosDelUsuario($usuario);

        foreach ($carritoSesion as $productoId => $it) {
            $producto = Producto::find($productoId);
            if (!$producto) continue;
            $precioBase = (float) ($producto->precio ?? 0);
            $productosCarrito[] = [
                'producto' => $producto,
                'cantidad' => (int) ($it['cantidad'] ?? 1),
                'precio_base' => $precioBase,
                'precio_con_descuento' => $this->aplicarDescuentos($precioBase, $descuentos),
            ];
        }

        return view('content.web.webcarrito', compact('productosCarrito', 'usuario', 'descuentos'));
    }

    // Agregar producto al carrito (session)
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|numeric|min:0.0001',
        ]);

        $producto = Producto::find($request->producto_id);
        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }

        $usuario = auth('web_usuario')->user();
        $descuentos = $this->descuentosDelUsuario($usuario);

        // calcular precio con descuentos en cascada
        $precioBase = (float) $producto->precio;
        $precioFinal = $this->aplicarDescuentos($precioBase, $descuentos);

        $carrito = session('carrito', []);
        $cantidad = (float) $request->cantidad;

        if (isset($carrito[$producto->id])) {
            $carrito[$producto->id]['cantidad'] = $carrito[$producto->id]['cantidad'] + $cantidad;
        } else {
            $carrito[$producto->id] = [
                'cantidad' => $cantidad,
                'precio_base' => $precioBase,
                'precio_con_descuento' => $precioFinal,
            ];
        }

        session(['carrito' => $carrito]);

        return response()->json(['success' => true, 'carrito' => $carrito]);
    }

    // Actualizar cantidad
    public function actualizar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|numeric|min:0',
        ]);

        $carrito = session('carrito', []);
        $id = $request->producto_id;
        $cantidad = (float) $request->cantidad;

        if (isset($carrito[$id])) {
            if ($cantidad <= 0) {
                unset($carrito[$id]);
            } else {
                $carrito[$id]['cantidad'] = $cantidad;
            }
            session(['carrito' => $carrito]);
            return response()->json(['success' => true, 'carrito' => $carrito]);
        }

        return response()->json(['success' => false, 'message' => 'Producto no en carrito'], 404);
    }

    // Eliminar
    public function eliminar(Request $request)
    {
        $request->validate(['producto_id' => 'required|integer']);
        $carrito = session('carrito', []);
        $id = $request->producto_id;
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito' => $carrito]);
        }
        return response()->json(['success' => true, 'carrito' => $carrito]);
    }

    // Vaciar carrito
    public function vaciar()
    {
        session()->forget('carrito');
        return response()->json(['success' => true]);
    }

    public function completar(Request $request)
    {
        $request->validate([
            'entrega' => 'required|in:retiro,reparto,convenir',
            'forma_pago' => 'required|in:contado,cuenta_corriente',
            'mensaje' => 'nullable|string|max:2000',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $usuario = auth('web_usuario')->user();
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        if (empty($usuario->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Tu usuario no tiene un email configurado para recibir la confirmacion.',
            ], 422);
        }

        // Email del dueño hardcodeado; email del cliente tomado de la BD
        $ownerEmail = 'martimanuel912@gmail.com';
        $clienteEmail = $usuario->email;

        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito esta vacio.',
            ], 422);
        }

        $descuentos = $this->descuentosDelUsuario($usuario);
        $items = [];
        $subtotalSinDescuento = 0;
        $subtotalConDescuentoItem = 0;

        foreach ($carrito as $productoId => $it) {
            $producto = Producto::find($productoId);
            if (!$producto) continue;

            $cantidad = max(1, (float) ($it['cantidad'] ?? 1));
            $precioBase = (float) ($producto->precio ?? 0);
            $precioConDescuento = $this->aplicarDescuentos($precioBase, $descuentos);
            $subtotalItem = $precioConDescuento * $cantidad;

            $subtotalSinDescuento += $precioBase * $cantidad;
            $subtotalConDescuentoItem += $subtotalItem;

            $items[] = [
                'codigo' => $producto->codigo ?? '-',
                'nombre' => $producto->nombre ?? '-',
                'precio_base' => $precioBase,
                'precio_con_descuento' => $precioConDescuento,
                'cantidad' => $cantidad,
                'subtotal' => $subtotalItem,
            ];
        }

        if (empty($items)) {
            session()->forget('carrito');

            return response()->json([
                'success' => false,
                'message' => 'No se encontraron productos validos en el carrito.',
            ], 422);
        }

        $descuentoFormaPagoPorcentaje = $request->forma_pago === 'contado' ? 5 : 0;
        $descuentoPorItems = $subtotalSinDescuento - $subtotalConDescuentoItem;
        $descuentoFormaPagoMonto = $subtotalConDescuentoItem * ($descuentoFormaPagoPorcentaje / 100);
        $subtotalFinal = $subtotalConDescuentoItem - $descuentoFormaPagoMonto;
        $iva = $subtotalFinal * 0.21;
        $total = $subtotalFinal + $iva;

        $entregas = [
            'retiro' => 'Retiro cliente',
            'reparto' => 'Reparto Resortes generales',
            'convenir' => 'A convenir',
        ];

        $formasPago = [
            'contado' => 'Contado',
            'cuenta_corriente' => 'Cuenta corriente',
        ];

        $pedido = [
            'cliente' => [
                'usuario' => $usuario->usuario ?? '-',
                'email' => $usuario->email ?? '-',
                'empresa' => $usuario->empresa ?? '-',
                'cuit' => $usuario->cuit ?? '-',
                'telefono' => $usuario->telefono ?? '-',
            ],
            'entrega' => $request->entrega,
            'entrega_label' => $entregas[$request->entrega] ?? $request->entrega,
            'forma_pago' => $request->forma_pago,
            'forma_pago_label' => $formasPago[$request->forma_pago] ?? $request->forma_pago,
            'mensaje' => $request->mensaje ?? '',
            'items' => $items,
            'totales' => [
                'subtotal_sin_descuento' => $subtotalSinDescuento,
                'descuento_items' => $descuentoPorItems,
                'descuento_forma_pago_porcentaje' => $descuentoFormaPagoPorcentaje,
                'descuento_forma_pago' => $descuentoFormaPagoMonto,
                'subtotal' => $subtotalFinal,
                'iva' => $iva,
                'total' => $total,
            ],
        ];

        $archivo = $request->file('archivo');


        try {
            // Envío simple y directo a las direcciones hardcodeadas
            Mail::to($clienteEmail)->send(new PedidoConfirmacionMail($pedido, 'cliente', $archivo));
            Mail::to($ownerEmail)->send(new PedidoConfirmacionMail($pedido, 'duenos', $archivo));
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar la confirmacion del pedido. Intentá nuevamente.',
            ], 500);
        }

        // Al completar el pedido, limpiar el carrito de la sesión
        session()->forget('carrito');

        return response()->json([
            'success' => true,
            'message' => 'Pedido realizado con exito. Enviamos la confirmacion por email.',
        ]);
    }
}
