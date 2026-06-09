<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductoFoto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Eager load marca to avoid N+1 and allow searching by marca
        $query = Producto::with('marca')->orderBy('orden');

        if ($request->filled('q')) {
            $term = $request->get('q');

            // Buscar por nombre o código, o por texto de la marca
            $query->where(function($sub) use ($term) {
                $sub->where('nombre', 'like', "%{$term}%")
                    ->orWhere('codigo', 'like', "%{$term}%");
            })->orWhereHas('marca', function($m) use ($term) {
                $m->where('nombre', 'like', "%{$term}%");
            });
        }

        $productos = $query->paginate(25)->withQueryString();

        return view('content.admin.producto', [
            'productos' => $productos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::orderBy('orden')->get();

        return view('content.admin.producto-creador', [
            'marcas' => $marcas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'archivo' => 'nullable|file|max:5120',
            'codigo' => 'nullable|string|max:255',
            'precio' => 'nullable|numeric',
        ]);

        $producto = new Producto();

        $producto->marca_id = $request->marca_id;
        $producto->orden = $request->orden;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;

        $producto->destacado = $request->has('destacado') ? (bool)$request->destacado : true;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $producto->foto = Storage::url($path);
        }

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files', $name, 'public');

            $producto->archivo = Storage::url($path);
        }

        $producto->active = $request->has('active') ? (bool)$request->active : true;

        $producto->save();
        return redirect()->route('adm.producto-editor', $producto->id)
            ->with('success', 'Producto creado correctamente. Ahora puedes agregar fotos.');
    }

    /**
     * Display the specified resource.
     */
    public function show($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);
        $marcas = Marca::orderBy('orden')->get();

        return view('content.admin.producto-editor', [
            'producto' => $producto,
            'marcas' => $marcas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        // Not used - using show() to load editor with id
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'archivo' => 'nullable|file|max:5120',
            'codigo' => 'nullable|string|max:255',
            'precio' => 'nullable|numeric',
        ]);

        $producto = Producto::find($request->producto_id) ?? new Producto();

        $producto->marca_id = $request->marca_id;
        $producto->orden = $request->orden;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;

        $producto->destacado = $request->has('destacado') ? (bool)$request->destacado : $producto->destacado;

        if ($request->hasFile('foto')) {

            if (!empty($producto->foto)) {
                $oldPath = str_replace('/storage/', '', $producto->foto);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $producto->foto = Storage::url($path);
        }

        if ($request->hasFile('archivo')) {

            if (!empty($producto->archivo)) {
                $oldPath = str_replace('/storage/', '', $producto->archivo);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('archivo');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files', $name, 'public');

            $producto->archivo = Storage::url($path);
        }

        $producto->active = $request->has('active') ? (bool)$request->active : $producto->active;

        $producto->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producto_id)
    {
        $producto = Producto::find($producto_id);

        if ($producto) {

            if (!empty($producto->foto)) {

                $imagePath = str_replace('/storage/', '', $producto->foto);

                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            if (!empty($producto->archivo)) {
                $filePath = str_replace('/storage/', '', $producto->archivo);

                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $producto->delete();
        }

        return redirect()->route('adm.producto')
            ->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Activar / desactivar.
     */
    public function switch($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);

        $producto->active = !$producto->active;

        $producto->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Alternar destacado.
     */
    public function destacado($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);

        $producto->destacado = !$producto->destacado;

        $producto->save();

        return redirect()->route('adm.producto')
            ->with('success', 'Destacado actualizado correctamente.');
    }

    /**
     * Reordenar.
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {

            Producto::where('id', $item['id'])
                ->update([
                    'orden' => $item['orden']
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }

    /**
     * Importar productos desde CSV/Excel.
     * Mapea columnas: codigo -> codigo, descripcion -> nombre, marca -> marca, precio -> precio
     */
    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt,xls,xlsx,ods',
        ]);

        $path = $request->file('archivo')->getRealPath();
        $extension = strtolower($request->file('archivo')->getClientOriginalExtension());

        $rows = [];

        if (in_array($extension, ['xls', 'xlsx', 'ods'])) {
            try {
                if (!class_exists('\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
                    return redirect()->back()->with('error', 'PhpSpreadsheet no está instalado. Instala phpoffice/phpspreadsheet para importar XLS/XLSX.');
                }

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, true, true, true);

                // Primera fila cabecera
                $header = [];
                if (isset($data[1])) {
                    foreach ($data[1] as $col => $val) {
                        $header[$col] = strtolower(trim((string)$val));
                    }
                }

                for ($i = 2; $i <= count($data); $i++) {
                    $row = [];
                    foreach ($data[$i] as $col => $val) {
                        if (isset($header[$col])) $row[$header[$col]] = $val;
                    }
                    if (count($row)) $rows[] = $row;
                }
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Error leyendo el archivo: ' . $e->getMessage());
            }
        } else {
            // CSV
            if (($handle = fopen($path, 'r')) !== false) {
                $header = null;
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    if (!$header) {
                        $header = array_map(function ($h) { return strtolower(trim($h)); }, $data);
                    } else {
                        $rows[] = array_combine($header, $data);
                    }
                }
                fclose($handle);
            }
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'No se encontraron filas para procesar.');
        }

        $creados = 0;
        $actualizados = 0;
        $errores = [];

        foreach ($rows as $index => $r) {
            try {
                $codigo = $r['codigo'] ?? ($r['ph'] ?? null);
                $nombre = $r['descripcion'] ?? ($r['descripcion_esp'] ?? ($r['nombre'] ?? null));
                $marcaNombre = $r['marca'] ?? null;
                $precioRaw = $r['precio'] ?? null;

                if (empty($codigo)) {
                    $errores[] = "Fila " . ($index + 2) . ": código vacío";
                    continue;
                }

                $producto = Producto::where('codigo', $codigo)->first();
                if (!$producto) {
                    $producto = new Producto();
                    $producto->orden = Producto::count() + 1;
                    $creados++;
                } else {
                    $actualizados++;
                }

                $producto->codigo = $codigo;
                if (!empty($nombre)) $producto->nombre = $nombre;

                if (!empty($precioRaw)) {
                    $precio = floatval(str_replace([',', ' '], ['.', ''], $precioRaw));
                    $producto->precio = $precio;
                }

                if (!empty($marcaNombre)) {
                    $marca = Marca::where('nombre', $marcaNombre)->first();

                    if ($marca) {
                        $producto->marca_id = $marca->id;
                    }
                }

                $producto->save();

            } catch (\Throwable $e) {
                $errores[] = "Fila " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $mensaje = "Importación completada: {$creados} creados, {$actualizados} actualizados";
        if (count($errores)) $mensaje .= " (" . count($errores) . " errores)";

        return redirect()->route('adm.producto')->with('success', $mensaje);
    }

    /**
     * Subir fotos para la galería del producto (AJAX)
     */
    public function fotosStore(Request $request)
    {
        $request->validate([
            'fotos' => 'required',
            'fotos.*' => 'image|max:2048',
            'productos_id' => 'required|integer',
        ]);

        $productos_id = $request->productos_id;
        $ordenInicial = intval($request->orden) ?? 0;

        $saved = [];
        foreach ($request->file('fotos') as $file) {
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('producto_fotos', $name, 'public');

            $foto = ProductoFoto::create([
                'productos_id' => $productos_id,
                'orden' => $ordenInicial,
                'foto' => Storage::url($path),
                'active' => true,
            ]);

            $saved[] = [
                'id' => $foto->id,
                'foto' => $foto->foto,
                'orden' => $foto->orden,
            ];
            $ordenInicial++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Fotos subidas correctamente',
            'fotos' => $saved
        ]);
    }

    /**
     * Reordenar fotos (AJAX)
     */
    public function fotosReordenar(Request $request)
    {
        foreach ($request->orden as $item) {
            ProductoFoto::where('id', $item['id'])->update(['orden' => $item['orden']]);
        }

        return response()->json(['success' => true, 'message' => 'Orden de fotos actualizado']);
    }

    /**
     * Eliminar foto de la galería
     */
    public function fotosDestroy($foto_id)
    {
        $foto = ProductoFoto::findOrFail($foto_id);

        if (!empty($foto->foto)) {
            $filePath = str_replace('/storage/', '', $foto->foto);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $foto->delete();

        return response()->json(['success' => true, 'message' => 'Foto eliminada correctamente']);
    }

    /**
     * Alternar activo/inactivo de una foto
     */
    public function fotosSwitch($foto_id)
    {
        $foto = ProductoFoto::findOrFail($foto_id);

        $foto->active = !$foto->active;
        $foto->save();

        return redirect()->route('adm.producto-editor', $foto->productos_id)
            ->with('success', 'Estado de la foto actualizado correctamente.');
    }

    /**
     * Eliminar archivo adjunto del producto.
     */
    public function deleteArchivo($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);

        if (!empty($producto->archivo)) {
            $filePath = str_replace('/storage/', '', $producto->archivo);

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $producto->archivo = null;
            $producto->save();
        }

        return redirect()->route('adm.producto-editor', $producto->id)
            ->with('success', 'Archivo eliminado correctamente.');
    }

    public function fotosMasivas(Request $request)
{
    $request->validate([
        'fotos'   => 'required|array',
        'fotos.*' => 'image|max:4096',
    ]);
 
    $asignadas  = 0;
    $sinProducto = 0;
    $errores    = 0;
 
    // Mapa: código (lowercase) => producto
    $productos = Producto::all()->keyBy(fn ($p) => strtolower(trim($p->codigo)));
 
    // Códigos que recibieron foto en esta carga
    $codigosConFoto = [];
 
    foreach ($request->file('fotos') as $file) {
        try {
            // Nombre del archivo sin extensión → código buscado
            $codigoBuscado = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
 
            if (!isset($productos[$codigoBuscado])) {
                $sinProducto++;
                continue;
            }
 
            $producto = $productos[$codigoBuscado];
 
            // Eliminar foto anterior si existe
            if (!empty($producto->foto)) {
                $oldPath = ltrim(str_replace('/storage', '', $producto->foto), '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
 
            // Guardar nueva foto
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $producto->foto = Storage::url($path);
            $producto->save();
 
            $codigosConFoto[] = strtolower(trim($producto->codigo));
            $asignadas++;
 
        } catch (\Throwable $e) {
            $errores++;
        }
    }
 
    // Productos activos sin foto coincidente → asignar default.png
    $defaultAsignados = 0;
    foreach ($productos as $codigo => $producto) {
        if (!in_array($codigo, $codigosConFoto)) {
            // Solo actualizar si aún no tiene foto o si su foto no existe
            if (empty($producto->foto)) {
                $producto->foto = '/default.png';
                $producto->save();
                $defaultAsignados++;
            }
        }
    }
 
    return redirect()->route('adm.producto')->with('fotos_masivas', [
        'asignadas'   => $asignadas,
        'default'     => $defaultAsignados,
        'sin_producto' => $sinProducto,
        'errores'     => $errores,
    ]);
}
}
