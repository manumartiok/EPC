<?php

namespace App\Http\Controllers;

use App\Models\Novedade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $novedades = Novedade::orderBy('orden')->get();

        return view('content.admin.novedade', [
            'novedades' => $novedades
        ]);
    }

    /**
     * Mostrar formulario para crear una nueva novedad.
     */
    public function create()
    {
        return view('content.admin.novedade-creador');
    }

    /**
     * Guardar una nueva novedad en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);

        $novedade = new Novedade();
        $novedade->orden = $request->orden;
        $novedade->texto_chico = $request->texto_chico;
        $novedade->titulo = $request->titulo;
        $novedade->texto = $request->texto;

        // Manejo de imagen
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $novedade->foto = Storage::url($path);
        }

        $novedade->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Novedad creada correctamente.');
    }

    /**
     * Mostrar el editor de una novedad específica.
     */
    public function show($novedade_id)
    {
        $novedade = Novedade::findOrFail($novedade_id);

        return view('content.admin.novedade-editor', [
            'novedade' => $novedade
        ]);
    }

    /**
     * Actualizar una novedad existente.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);

        $novedade = Novedade::find($request->novedade_id) ?? new Novedade();

        $novedade->orden = $request->orden;
        $novedade->texto_chico = $request->texto_chico;
        $novedade->titulo = $request->titulo;
        $novedade->texto = $request->texto;

        // Manejo de imagen
        if ($request->hasFile('foto')) {

            if (!empty($novedade->foto)) {
                $oldPath = str_replace('/storage/', '', $novedade->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $novedade->foto = Storage::url($path);
        }

        $novedade->save();

        return redirect()->route('adm.novedade', [
            'novedade' => $novedade->id
        ])->with('success', 'Novedad actualizada correctamente.');
    }

    /**
     * Eliminar una novedad.
     */
    public function destroy($novedade_id)
    {
        $novedade = Novedade::find($novedade_id);

        if ($novedade) {

            if (!empty($novedade->foto)) {
                $imagePath = str_replace('/storage/', '', $novedade->foto);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $novedade->delete();
        }

        return redirect()->route('adm.novedade')
            ->with('success', 'Novedad eliminada correctamente.');
    }

    /**
     * Activar o desactivar una novedad.
     */
    public function switch($novedade_id)
    {
        $novedade = Novedade::findOrFail($novedade_id);
        $novedade->active = !$novedade->active;
        $novedade->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Estado de la novedad actualizado correctamente.');
    }

    /**
     * Reordenar novedades (drag & drop).
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {
            Novedade::where('id', $item['id'])
                ->update(['orden' => $item['orden']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }

    /**
     * Marcar o desmarcar una novedad como destacada.
     */
    public function destacado($novedade_id)
    {
        $novedade = Novedade::findOrFail($novedade_id);
        $novedade->destacado = !$novedade->destacado;
        $novedade->save();

        return redirect()->route('adm.novedade')
            ->with('success', 'Destacado de la novedad actualizado correctamente.');
    }

    /**
     * Detalle web de la novedad.
     */
    public function detalle($id)
    {
        $novedade = Novedade::findOrFail($id);

        return view('content.web.novedade-detalle', compact('novedade'));
    }
}
