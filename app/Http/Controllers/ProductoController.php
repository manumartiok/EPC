<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProductoFoto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::orderBy('orden');

        if ($request->filled('q')) {
            $term = $request->get('q');

            $query->where(function ($sub) use ($term) {
                $sub->where('nombre', 'like', "%{$term}%")
                    ->orWhere('nombre_completo', 'like', "%{$term}%");
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
        return view('content.admin.producto-creador', [
            'producto' => new Producto(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validar($request);

        $producto = new Producto();
        $producto->active = $request->has('active') ? (bool)$request->active : true;
        $producto->destacado = $request->has('destacado') ? (bool)$request->destacado : true;

        $this->guardarCampos($producto, $request);

        return redirect()->route('adm.producto-editor', $producto->id)
            ->with('success', 'Producto creado correctamente. Ahora puedes agregar fotos.');
    }

    /**
     * Display the specified resource.
     */
    public function show($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);

        return view('content.admin.producto-editor', [
            'producto' => $producto,
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
    public function update(Request $request)
    {
        $this->validar($request);

        $producto = Producto::findOrFail($request->producto_id);

        $producto->destacado = $request->has('destacado') ? (bool)$request->destacado : $producto->destacado;
        $producto->active = $request->has('active') ? (bool)$request->active : $producto->active;

        $this->guardarCampos($producto, $request);

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
            $this->borrarArchivo($producto->foto);
            $this->borrarArchivo($producto->archivo);

            foreach ($producto->caracteristicas ?? [] as $caracteristica) {
                $this->borrarArchivo($caracteristica['foto'] ?? null);
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
     * Subir fotos para la galería del producto (AJAX)
     */
    public function fotosStore(Request $request)
    {
        $request->validate([
            'fotos' => 'required',
            'fotos.*' => 'file|mimes:jpg,jpeg,png,webp,gif,mp4,webm|max:51200',
            'productos_id' => 'required|integer',
        ]);

        // Las fotos pueden pesar hasta 2MB; los videos (mp4, webm) hasta 50MB
        foreach ($request->file('fotos') as $file) {
            if (!ProductoFoto::urlEsVideo($file->getClientOriginalName()) && $file->getSize() > 2 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'La foto "' . $file->getClientOriginalName() . '" supera los 2MB.',
                ], 422);
            }
        }

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
            'message' => 'Archivos subidos correctamente',
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

        $this->borrarArchivo($foto->foto);

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
            $this->borrarArchivo($producto->archivo);

            $producto->archivo = null;
            $producto->save();
        }

        return redirect()->route('adm.producto-editor', $producto->id)
            ->with('success', 'Archivo eliminado correctamente.');
    }

    // ============================================================
    // Helpers
    // ============================================================

    private function validar(Request $request): void
    {
        $request->validate([
            'foto'                   => 'nullable|image|max:2048',
            'archivo'                => 'nullable|file|mimes:pdf|max:10240',
            'caracteristicas'        => 'nullable|array',
            'caracteristicas.*.foto' => 'nullable|image|max:1024',
        ]);
    }

    /**
     * Asigna textos, archivos y los tres json (ficha técnica, características, colores) y guarda.
     */
    private function guardarCampos(Producto $producto, Request $request): void
    {
        $producto->orden           = $request->orden;
        $producto->nombre          = $request->nombre;
        $producto->nombre_completo = $request->nombre_completo;
        $producto->descripcion     = $request->descripcion;
        $producto->aplicacion      = $request->aplicacion;
        $producto->certificado     = $request->certificado;
        $producto->tamaños         = $request->input('tamaños');

        // Foto principal
        if ($request->hasFile('foto')) {
            $this->borrarArchivo($producto->foto);
            $producto->foto = $this->subirArchivo($request->file('foto'), 'images');
        }

        // Ficha técnica (PDF)
        if ($request->hasFile('archivo')) {
            $this->borrarArchivo($producto->archivo);
            $producto->archivo = $this->subirArchivo($request->file('archivo'), 'files');
        }

        $producto->ficha_tecnica   = $this->armarFichaTecnica($request);
        $producto->caracteristicas = $this->armarCaracteristicas($request, $producto);
        $producto->colores         = $this->armarColores($request);

        $producto->save();
    }

    /**
     * ficha_tecnica: [{nombre, valor}]
     */
    private function armarFichaTecnica(Request $request): array
    {
        $filas = [];

        foreach ($request->input('ficha_tecnica', []) as $fila) {
            $nombre = trim($fila['nombre'] ?? '');
            $valor  = trim($fila['valor'] ?? '');

            if ($nombre === '' && $valor === '') {
                continue;
            }

            $filas[] = ['nombre' => $nombre, 'valor' => $valor];
        }

        return $filas;
    }

    /**
     * caracteristicas: [{foto, titulo}]
     * Las fotos que ya no se usan se borran del storage.
     */
    private function armarCaracteristicas(Request $request, Producto $producto): array
    {
        $previas = collect($producto->caracteristicas ?? [])->pluck('foto')->filter()->all();
        $filas = [];

        foreach ($request->input('caracteristicas', []) as $i => $fila) {
            $titulo = trim($fila['titulo'] ?? '');

            // Solo se conserva una foto que ya pertenecía al producto
            $foto = $fila['foto_actual'] ?? null;
            if (!in_array($foto, $previas, true)) {
                $foto = null;
            }

            if ($request->hasFile("caracteristicas.$i.foto")) {
                $foto = $this->subirArchivo($request->file("caracteristicas.$i.foto"), 'images');
            }

            if ($titulo === '' && !$foto) {
                continue;
            }

            $filas[] = ['foto' => $foto, 'titulo' => $titulo];
        }

        $conservadas = array_column($filas, 'foto');
        foreach ($previas as $previa) {
            if (!in_array($previa, $conservadas, true)) {
                $this->borrarArchivo($previa);
            }
        }

        return $filas;
    }

    /**
     * colores: [{tipo, colores: [clave, ...]}], con claves de Producto::COLORES
     */
    private function armarColores(Request $request): array
    {
        $filas = [];

        foreach ($request->input('colores', []) as $fila) {
            $tipo = trim($fila['tipo'] ?? '');
            if ($tipo === '') {
                continue;
            }

            $colores = array_values(array_intersect(
                array_keys(Producto::COLORES),
                (array) ($fila['colores'] ?? [])
            ));

            $filas[] = ['tipo' => $tipo, 'colores' => $colores];
        }

        return $filas;
    }

    private function subirArchivo($file, string $carpeta): string
    {
        $nombre = time() . '_' . Str::random(4) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();

        return Storage::url($file->storeAs($carpeta, $nombre, 'public'));
    }

    private function borrarArchivo(?string $url): void
    {
        if (empty($url)) {
            return;
        }

        Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $url), '/'));
    }
}
