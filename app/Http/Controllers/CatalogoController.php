<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class CatalogoController extends Controller
{
    /**
     * Mostrar el recurso.
     */
    public function show($catalogo_id = null)
    {
        $catalogo = Catalogo::first() ?? new Catalogo();
        return view('content.admin.catalogo', ['catalogo' => $catalogo]);
    }

    /**
     * Actualizar o crear el recurso.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto'                 => 'nullable|image|max:2048',
            'archivo'              => 'nullable|file|mimes:pdf|max:51200',
            'categorias'           => 'nullable|array',
            'categorias.*.nombre'  => 'nullable|string|max:255',
            'categorias.*.archivo' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        // Obtener o crear registro
        $catalogo = Catalogo::find($request->catalogo_id) ?? new Catalogo();

        // ============================
        // TEXTOS
        // ============================
        $catalogo->titulo1             = $request->input('titulo1');
        $catalogo->subtitulo1          = $request->input('subtitulo1');
        $catalogo->texto               = $request->input('texto');

        $catalogo->titulo_archivo      = $request->input('titulo_archivo');
        $catalogo->subtitulo_archivo   = $request->input('subtitulo_archivo');
        $catalogo->descripcion_archivo = $request->input('descripcion_archivo');

        $catalogo->titulo2             = $request->input('titulo2');
        $catalogo->subtitulo2          = $request->input('subtitulo2');

        // ============================
        // PORTADA
        // ============================
        if ($request->hasFile('foto')) {
            $this->borrarArchivo($catalogo->foto);
            $catalogo->foto = $this->guardarArchivo($request->file('foto'), 'images');
        }

        // ============================
        // CATÁLOGO COMPLETO (PDF)
        // páginas, peso y fecha salen del propio archivo al subirlo
        // ============================
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');

            $this->borrarArchivo($catalogo->archivo);

            $catalogo->peso          = $file->getSize();
            $catalogo->paginas       = $this->contarPaginas($file->getRealPath());
            $catalogo->fecha_archivo = now()->toDateString();
            $catalogo->archivo       = $this->guardarArchivo($file, 'catalogo');
        }

        // ============================
        // CATEGORÍAS: [{nombre, archivo}]
        // ============================
        $archivosPrevios = collect($catalogo->categorias ?? [])->pluck('archivo')->filter()->all();
        $categorias = [];

        foreach ($request->input('categorias', []) as $i => $fila) {
            $nombre = trim($fila['nombre'] ?? '');
            if ($nombre === '') {
                continue;
            }

            // Solo se conserva un archivo que ya pertenecía a las categorías
            $archivo = $fila['archivo_actual'] ?? null;
            if (!in_array($archivo, $archivosPrevios, true)) {
                $archivo = null;
            }

            if ($request->hasFile("categorias.$i.archivo")) {
                $archivo = $this->guardarArchivo($request->file("categorias.$i.archivo"), 'catalogo');
            }

            $categorias[] = ['nombre' => $nombre, 'archivo' => $archivo];
        }

        // Borrar archivos de categorías quitadas o reemplazadas
        $conservados = array_column($categorias, 'archivo');
        foreach ($archivosPrevios as $previo) {
            if (!in_array($previo, $conservados, true)) {
                $this->borrarArchivo($previo);
            }
        }

        $catalogo->categorias = $categorias;

        // Guardar todo
        $catalogo->save();

        return redirect()
            ->route('adm.catalogo', ['catalogo_id' => $catalogo->id])
            ->with('success', 'Catálogo actualizado correctamente.');
    }

    private function guardarArchivo($file, string $carpeta): string
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

        Storage::disk('public')->delete(str_replace('/storage/', '', $url));
    }

    private function contarPaginas(string $ruta): ?int
    {
        try {
            return count((new Parser())->parseFile($ruta)->getPages()) ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
