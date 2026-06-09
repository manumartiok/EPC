<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listas = Lista::orderBy('orden')->get();

        return view('content.admin.lista', [
            'listas' => $listas
        ]);
    }

    /**
     * Mostrar formulario para crear una nueva lista.
     */
    public function create()
    {
        return view('content.admin.lista-creador');
    }

    /**
     * Guardar una nueva lista.
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'nullable|file|max:10240', // acepta cualquier archivo (10MB)
        ]);

        $lista = new Lista();
        $lista->orden  = $request->orden;
        $lista->nombre = $request->nombre;

        // Manejo de archivo (cualquier tipo)
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('archivos', $name, 'public');
            $lista->archivo = Storage::url($path);
        }

        $lista->save();

        return redirect()->route('adm.lista')
            ->with('success', 'Registro creado correctamente.');
    }

    /**
     * Mostrar editor de una lista específica.
     */
    public function show($lista_id)
    {
        $lista = Lista::findOrFail($lista_id);

        return view('content.admin.lista-editor', [
            'lista' => $lista
        ]);
    }

    /**
     * Actualizar una lista existente.
     */
    public function update(Request $request)
    {
        $request->validate([
            'archivo' => 'nullable|file|max:10240',
        ]);

        $lista = Lista::find($request->lista_id) ?? new Lista();

        $lista->orden  = $request->orden;
        $lista->nombre = $request->nombre;

        // Manejo de archivo
        if ($request->hasFile('archivo')) {

            if (!empty($lista->archivo)) {
                $oldPath = str_replace('/storage/', '', $lista->archivo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('archivo');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('archivos', $name, 'public');
            $lista->archivo = Storage::url($path);
        }

        $lista->save();

        return redirect()->route('adm.lista', [
            'lista' => $lista->id
        ])->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Eliminar una lista.
     */
    public function destroy($lista_id)
    {
        $lista = Lista::find($lista_id);

        if ($lista) {

            if (!empty($lista->archivo)) {
                $path = str_replace('/storage/', '', $lista->archivo);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $lista->delete();
        }

        return redirect()->route('adm.lista')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Activar / desactivar lista.
     */
    public function switch($lista_id)
    {
        $lista = Lista::findOrFail($lista_id);
        $lista->active = !$lista->active;
        $lista->save();

        return redirect()->route('adm.lista')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Reordenar listas (drag & drop).
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {
            Lista::where('id', $item['id'])
                ->update(['orden' => $item['orden']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }
}
