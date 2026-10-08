<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoBusquedaController extends Controller
{
    public function productos(Request $request)
    {
        $buscar = trim((string) $request->get('buscar', ''));

        $productos = $this->filtrarProductos($request)
            ->orderBy('orden')
            ->get();

        return view('content.web.productos', compact('productos', 'buscar'));
    }

    public function detalle($id)
    {
        $producto = Producto::with('fotos')
            ->where('active', true)
            ->findOrFail($id);

        return view('content.web.productos-detalle', compact('producto'));
    }

    public function webProductos(Request $request)
    {
        $usuario = auth('web_usuario')->user();
        $descuentos = collect(range(1, 5))
            ->map(fn ($i) => (float) data_get($usuario, "descuento{$i}", 0))
            ->filter(fn ($d) => $d > 0)
            ->values()
            ->all();

        $marcas = $this->marcas();
        $productos = $this->filtrarProductos($request)
            ->orderBy('orden')
            ->paginate(10)
            ->withQueryString();

        return view('content.web.webproductos', compact('productos', 'descuentos', 'marcas'));
    }

    private function marcas()
    {
        return Marca::where('active', true)
            ->orderBy('orden')
            ->get();
    }

    private function filtrarProductos(Request $request)
    {
        $query = Producto::where('active', true);

        if ($request->filled('buscar')) {
            $buscar = trim($request->get('buscar'));
            $query->where(function ($sub) use ($buscar) {
                $sub->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('nombre_completo', 'like', "%{$buscar}%");
            });
        }

        return $query;
    }
}
