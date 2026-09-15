<?php

namespace App\Http\Controllers;

use App\Mail\PedidoConfirmacionMail;
use App\Models\CarritoPendiente;
use App\Models\Pedido;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $precio *= 1 - ($descuento / 100);
        }

        return $precio;
    }

    private function cargarCarritoPendiente($usuario): array
    {
        if (!$usuario) {
            return session('carrito', []);
        }

        $carrito = session('carrito', []);
        if (!empty($carrito)) {
            $this->guardarCarritoPendiente($usuario, $carrito);
            return $carrito;
        }

        $pendiente = CarritoPendiente::where('usuario_id', $usuario->id)
            ->whereNull('cliente_id')
            ->first();
        $carrito = $pendiente?->items ?: [];

        if (!empty($carrito)) {
            session(['carrito' => $carrito]);
        }

        return $carrito;
    }

    private function guardarCarritoPendiente($usuario, array $carrito): void
    {
        if (!$usuario) {
            return;
        }

        $query = CarritoPendiente::where('usuario_id', $usuario->id)->whereNull('cliente_id');
        if (empty($carrito)) {
            $query->delete();
            return;
        }

        CarritoPendiente::updateOrCreate(
            ['usuario_id' => $usuario->id, 'cliente_id' => null],
            ['items' => $carrito]
        );
    }

    public function index()
    {
        $usuario = auth('web_usuario')->user();
        $carrito = $this->cargarCarritoPendiente($usuario);
        $descuentos = $this->descuentosDelUsuario($usuario);
        $productosCarrito = [];

        foreach ($carrito as $productoId => $item) {
            $producto = Producto::where('active', true)->find($productoId);
            if (!$producto) {
                continue;
            }

            $precioBase = (float) ($producto->precio ?? 0);
            $productosCarrito[] = [
                'producto' => $producto,
                'cantidad' => (int) ($item['cantidad'] ?? 1),
                'precio_base' => $precioBase,
                'precio_con_descuento' => $this->aplicarDescuentos($precioBase, $descuentos),
            ];
        }

        return view('content.web.webcarrito', compact('productosCarrito', 'usuario', 'descuentos'));
    }

    public function agregar(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::where('active', true)->find($validated['producto_id']);
        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }

        $usuario = auth('web_usuario')->user();
        $carrito = $this->cargarCarritoPendiente($usuario);
        $cantidad = (int) $validated['cantidad'];
        $precioBase = (float) $producto->precio;

        if (isset($carrito[$producto->id])) {
            $carrito[$producto->id]['cantidad'] += $cantidad;
        } else {
            $carrito[$producto->id] = [
                'cantidad' => $cantidad,
                'precio_base' => $precioBase,
                'precio_con_descuento' => $this->aplicarDescuentos($precioBase, $this->descuentosDelUsuario($usuario)),
            ];
        }

        session(['carrito' => $carrito]);
        $this->guardarCarritoPendiente($usuario, $carrito);

        return response()->json(['success' => true, 'carrito' => $carrito]);
    }

    public function actualizar(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:0',
        ]);

        $usuario = auth('web_usuario')->user();
        $carrito = $this->cargarCarritoPendiente($usuario);
        $productoId = (int) $validated['producto_id'];
        $cantidad = (int) $validated['cantidad'];

        if (!isset($carrito[$productoId])) {
            return response()->json(['success' => false, 'message' => 'Producto no en carrito'], 404);
        }

        if ($cantidad === 0) {
            unset($carrito[$productoId]);
        } else {
            $carrito[$productoId]['cantidad'] = $cantidad;
        }

        session(['carrito' => $carrito]);
        $this->guardarCarritoPendiente($usuario, $carrito);

        return response()->json(['success' => true, 'carrito' => $carrito]);
    }

    public function eliminar(Request $request)
    {
        $validated = $request->validate(['producto_id' => 'required|integer']);
        $usuario = auth('web_usuario')->user();
        $carrito = $this->cargarCarritoPendiente($usuario);
        unset($carrito[(int) $validated['producto_id']]);

        session(['carrito' => $carrito]);
        $this->guardarCarritoPendiente($usuario, $carrito);

        return response()->json(['success' => true, 'carrito' => $carrito]);
    }

    public function vaciar()
    {
        $usuario = auth('web_usuario')->user();
        session()->forget('carrito');
        $this->guardarCarritoPendiente($usuario, []);

        return response()->json(['success' => true]);
    }

    public function completar(Request $request)
    {
        $request->validate([
            'entrega' => 'required|in:retiro,reparto,convenir',
            'forma_pago' => 'required|in:contado,cuenta_corriente',
            'mensaje' => 'nullable|string|max:2000',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $usuario = auth('web_usuario')->user();
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
        }

        if (empty($usuario->email)) {
            return response()->json(['success' => false, 'message' => 'Tu usuario no tiene un email configurado.'], 422);
        }

        $carrito = $this->cargarCarritoPendiente($usuario);
        if (empty($carrito)) {
            return response()->json(['success' => false, 'message' => 'El carrito esta vacio.'], 422);
        }

        $descuentos = $this->descuentosDelUsuario($usuario);
        $items = [];
        $subtotalSinDescuento = 0;
        $subtotalConDescuentoItem = 0;

        foreach ($carrito as $productoId => $item) {
            $producto = Producto::where('active', true)->find($productoId);
            if (!$producto) {
                continue;
            }

            $cantidad = (int) ($item['cantidad'] ?? 0);
            if ($cantidad < 1) {
                continue;
            }

            $precioBase = (float) $producto->precio;
            $precioConDescuento = $this->aplicarDescuentos($precioBase, $descuentos);
            $subtotalItem = $precioConDescuento * $cantidad;
            $subtotalSinDescuento += $precioBase * $cantidad;
            $subtotalConDescuentoItem += $subtotalItem;

            $items[] = [
                'producto_id' => $producto->id,
                'codigo' => $producto->codigo ?? '-',
                'nombre' => $producto->nombre ?? '-',
                'precio_base' => $precioBase,
                'precio_con_descuento' => $precioConDescuento,
                'cantidad' => $cantidad,
                'subtotal' => $subtotalItem,
            ];
        }

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'No se encontraron productos validos en el carrito.'], 422);
        }

        $descuentoFormaPagoPorcentaje = $request->forma_pago === 'contado' ? 5 : 0;
        $descuentoPorItems = $subtotalSinDescuento - $subtotalConDescuentoItem;
        $descuentoFormaPago = $subtotalConDescuentoItem * ($descuentoFormaPagoPorcentaje / 100);
        $subtotalFinal = $subtotalConDescuentoItem - $descuentoFormaPago;
        $iva = $subtotalFinal * 0.21;
        $total = $subtotalFinal + $iva;

        $pedido = [
            'cliente' => [
                'usuario' => $usuario->usuario ?? '-',
                'email' => $usuario->email,
                'empresa' => $usuario->empresa ?? '-',
                'cuit' => $usuario->cuit ?? '-',
                'telefono' => $usuario->telefono ?? '-',
            ],
            'entrega' => $request->entrega,
            'entrega_label' => ['retiro' => 'Retiro cliente', 'reparto' => 'Reparto', 'convenir' => 'A convenir'][$request->entrega],
            'forma_pago' => $request->forma_pago,
            'forma_pago_label' => ['contado' => 'Contado', 'cuenta_corriente' => 'Cuenta corriente'][$request->forma_pago],
            'mensaje' => $request->mensaje ?? '',
            'items' => $items,
            'totales' => [
                'subtotal_sin_descuento' => $subtotalSinDescuento,
                'descuento_items' => $descuentoPorItems,
                'descuento_forma_pago_porcentaje' => $descuentoFormaPagoPorcentaje,
                'descuento_forma_pago' => $descuentoFormaPago,
                'subtotal' => $subtotalFinal,
                'iva' => $iva,
                'total' => $total,
            ],
        ];

        try {
            DB::transaction(function () use ($usuario, $request, $subtotalFinal, $iva, $total, $pedido, $items, $carrito) {
                $pedidoModel = Pedido::create([
                    'usuario_id' => $usuario->id,
                    'cliente_id' => $usuario->id,
                    'estado' => 'en_preparacion',
                    'entrega' => $request->entrega,
                    'forma_pago' => $request->forma_pago,
                    'mensaje' => $request->mensaje ?? '',
                    'subtotal' => $subtotalFinal,
                    'iva' => $iva,
                    'total' => $total,
                    'totales' => $pedido['totales'],
                ]);

                foreach ($items as $item) {
                    $pedidoModel->items()->create([
                        'producto_id' => $item['producto_id'],
                        'codigo' => $item['codigo'],
                        'nombre' => $item['nombre'],
                        'cantidad' => $item['cantidad'],
                        'precio_base' => $item['precio_base'],
                        'precio_con_descuento' => $item['precio_con_descuento'],
                        'subtotal' => $item['subtotal'],
                        'carrito_item' => $carrito[$item['producto_id']] ?? null,
                    ]);
                }
            });
        } catch (Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'No se pudo registrar el pedido.'], 500);
        }

        session()->forget('carrito');
        $this->guardarCarritoPendiente($usuario, []);

        $ownerEmail = config('mail.orders_recipient') ?: config('mail.from.address');
        $erroresCorreo = [];
        try {
            Mail::to($usuario->email)->send(new PedidoConfirmacionMail($pedido, 'cliente', $request->file('archivo')));
        } catch (Throwable $e) {
            report($e);
            $erroresCorreo[] = 'cliente';
        }
        try {
            Mail::to($ownerEmail)->send(new PedidoConfirmacionMail($pedido, 'duenos', $request->file('archivo')));
        } catch (Throwable $e) {
            report($e);
            $erroresCorreo[] = 'administracion';
        }

        return response()->json([
            'success' => true,
            'message' => empty($erroresCorreo)
                ? 'Pedido realizado con exito.'
                : 'Pedido realizado, pero no se pudo enviar uno de los emails.',
        ]);
    }

    public function historico()
    {
        $usuario = auth('web_usuario')->user();
        $pedidos = Pedido::with('items')->where('cliente_id', $usuario->id)->latest()->paginate(10);
        return view('content.web.webpedidos', compact('pedidos', 'usuario'));
    }

    public function pdf(Pedido $pedido)
    {
        $usuario = auth('web_usuario')->user();
        abort_unless($usuario && (int) $pedido->cliente_id === (int) $usuario->id, 403);
        $pedido->load(['usuario', 'cliente', 'items']);
        return Pdf::loadView('content.pedidos.pdf', compact('pedido'))
            ->stream('pedido-' . str_pad($pedido->id, 8, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function recomprar(Pedido $pedido)
    {
        $usuario = auth('web_usuario')->user();
        abort_unless($usuario && (int) $pedido->cliente_id === (int) $usuario->id, 403);

        $carrito = [];
        foreach ($pedido->items as $item) {
            if ($item->producto_id && $item->cantidad > 0) {
                $carrito[$item->producto_id] = [
                    'cantidad' => (int) $item->cantidad,
                    'precio_base' => $item->precio_base,
                    'precio_con_descuento' => $item->precio_con_descuento,
                ];
            }
        }

        session(['carrito' => $carrito]);
        $this->guardarCarritoPendiente($usuario, $carrito);
        return redirect()->route('web-carrito');
    }

    public function adminIndex(Request $request)
    {
        $buscar = trim((string) $request->get('q', ''));
        $pedidos = Pedido::with(['cliente', 'usuario', 'items'])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($query) use ($buscar) {
                    if (ctype_digit($buscar)) {
                        $query->orWhere('id', (int) $buscar);
                    }
                    $query->orWhereHas('cliente', fn ($cliente) => $cliente
                        ->where('usuario', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhere('empresa', 'like', "%{$buscar}%")
                        ->orWhere('cuit', 'like', "%{$buscar}%"))
                        ->orWhereHas('items', fn ($items) => $items
                            ->where('codigo', 'like', "%{$buscar}%")
                            ->orWhere('nombre', 'like', "%{$buscar}%"));
                });
            })
            ->latest()->paginate(15)->withQueryString();

        return view('content.admin.historico', compact('pedidos'));
    }

    public function adminShow(Pedido $pedido)
    {
        $pedido->load(['cliente', 'usuario', 'items']);
        return view('content.admin.historico-editor', compact('pedido'));
    }

    public function adminUpdate(Request $request)
    {
        $validated = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'fecha_entrega' => 'nullable|date',
            'estado' => 'required|in:en_preparacion,entregado,cancelado',
        ]);

        Pedido::findOrFail($validated['pedido_id'])->update([
            'fecha_entrega' => $validated['fecha_entrega'] ?? null,
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('adm.pedidos-historico-editor', $validated['pedido_id'])
            ->with('success', 'Pedido actualizado correctamente.');
    }

    public function adminDestroy(Pedido $pedido)
    {
        $pedido->delete();
        return redirect()->route('adm.pedidos-historico')->with('success', 'Pedido eliminado correctamente.');
    }

    public function adminSwitch(Pedido $pedido)
    {
        $pedido->update(['estado' => $pedido->estado === 'entregado' ? 'en_preparacion' : 'entregado']);
        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
