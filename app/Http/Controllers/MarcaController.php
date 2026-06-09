<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::orderBy('orden')->get();

        return view('content.admin.marca', [
            'marcas' => $marcas
        ]);
    }

    /**
     * Mostrar formulario para crear una nueva marca.
     */
    public function create()
    {
        return view('content.admin.marca-creador');
    }

    /**
     * Guardar una nueva marca.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);

        $marca = new Marca();

        $marca->orden = $request->orden;
        $marca->nombre = $request->nombre;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $marca->foto = Storage::url($path);
        }

        $marca->save();

        return redirect()->route('adm.marca')
            ->with('success', 'Marca creada correctamente.');
    }

    /**
     * Mostrar editor.
     */
    public function show($marca_id)
    {
        $marca = Marca::findOrFail($marca_id);

        return view('content.admin.marca-editor', [
            'marca' => $marca
        ]);
    }

    /**
     * Actualizar marca.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
        ]);

        $marca = Marca::find($request->marca_id) ?? new Marca();

        $marca->orden = $request->orden;
        $marca->nombre = $request->nombre;

        if ($request->hasFile('foto')) {

            if (!empty($marca->foto)) {
                $oldPath = str_replace('/storage/', '', $marca->foto);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $marca->foto = Storage::url($path);
        }

        $marca->save();

        return redirect()->route('adm.marca')
            ->with('success', 'Marca actualizada correctamente.');
    }

    /**
     * Eliminar marca.
     */
    public function destroy($marca_id)
    {
        $marca = Marca::find($marca_id);

        if ($marca) {

            if (!empty($marca->foto)) {

                $imagePath = str_replace('/storage/', '', $marca->foto);

                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $marca->delete();
        }

        return redirect()->route('adm.marca')
            ->with('success', 'Marca eliminada correctamente.');
    }

    /**
     * Activar / desactivar.
     */
    public function switch($marca_id)
    {
        $marca = Marca::findOrFail($marca_id);

        $marca->active = !$marca->active;

        $marca->save();

        return redirect()->route('adm.marca')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Reordenar.
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {

            Marca::where('id', $item['id'])
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
     * Destacado.
     */
    public function destacado($marca_id)
    {
        $marca = Marca::findOrFail($marca_id);

        $marca->destacado = !$marca->destacado;

        $marca->save();

        return redirect()->route('adm.marca')
            ->with('success', 'Destacado actualizado correctamente.');
    }
}