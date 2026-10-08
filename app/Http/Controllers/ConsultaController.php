<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultas = Consulta::orderBy('orden')->get();

        return view('content.admin.consulta', [
            'consultas' => $consultas
        ]);
    }

    /**
     * Mostrar formulario para crear un nuevo motivo de consulta.
     */
    public function create()
    {
        return view('content.admin.consulta-creador');
    }

    /**
     * Guardar un nuevo motivo de consulta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $consulta = new Consulta();

        $consulta->orden = $request->orden;
        $consulta->nombre = $request->nombre;

        $consulta->save();

        return redirect()->route('adm.consulta')
            ->with('success', 'Motivo de consulta creado correctamente.');
    }

    /**
     * Mostrar editor.
     */
    public function show($consulta_id)
    {
        $consulta = Consulta::findOrFail($consulta_id);

        return view('content.admin.consulta-editor', [
            'consulta' => $consulta
        ]);
    }

    /**
     * Actualizar motivo de consulta.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $consulta = Consulta::findOrFail($request->consulta_id);

        $consulta->orden = $request->orden;
        $consulta->nombre = $request->nombre;

        $consulta->save();

        return redirect()->route('adm.consulta')
            ->with('success', 'Motivo de consulta actualizado correctamente.');
    }

    /**
     * Eliminar motivo de consulta.
     */
    public function destroy($consulta_id)
    {
        $consulta = Consulta::find($consulta_id);

        if ($consulta) {
            $consulta->delete();
        }

        return redirect()->route('adm.consulta')
            ->with('success', 'Motivo de consulta eliminado correctamente.');
    }

    /**
     * Activar / desactivar.
     */
    public function switch($consulta_id)
    {
        $consulta = Consulta::findOrFail($consulta_id);

        $consulta->active = !$consulta->active;

        $consulta->save();

        return redirect()->route('adm.consulta')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Reordenar.
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {

            Consulta::where('id', $item['id'])
                ->update([
                    'orden' => $item['orden']
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }
}
