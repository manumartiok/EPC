<?php

namespace App\Http\Controllers;

use App\Models\Nosotro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NosotroController extends Controller
{
    /**
     * Mostrar el recurso.
     */
    public function show($nosotros_id = null)
    {
        $nosotros = Nosotro::first() ?? new Nosotro();
        return view('content.admin.nosotros', ['nosotro' => $nosotros]);
    }

    /**
     * Actualizar o crear el recurso.
     */
    public function update(Request $request)
    {
        $request->validate([
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:51200', // hasta 50 MB aprox
            'foto'   => 'nullable|image|max:2048',
            'icono1' => 'nullable|image|max:2048',
            'icono2' => 'nullable|image|max:2048',
            'icono3' => 'nullable|image|max:2048',
        ]);

        // Obtener o crear registro
        $nosotros = Nosotro::find($request->nosotro_id) ?? new Nosotro();

        // ============================
        // CAMPOS DIRECTOS (SIN MULTILENGUAJE)
        // ============================
        $nosotros->texto1        = $request->input('texto1');
        $nosotros->descripcion   = $request->input('descripcion');
        $nosotros->texto2        = $request->input('texto2');

        $nosotros->nombre_icono1 = $request->input('nombre_icono1');
        $nosotros->texto_icono1  = $request->input('texto_icono1');

        $nosotros->nombre_icono2 = $request->input('nombre_icono2');
        $nosotros->texto_icono2  = $request->input('texto_icono2');

        $nosotros->nombre_icono3 = $request->input('nombre_icono3');
        $nosotros->texto_icono3  = $request->input('texto_icono3');

        // ============================
        // ARCHIVOS (FOTO + ICONOS)
        // ============================
        $archivos = [
            'video' => 'videos',
            'foto'   => 'images',
            'icono1' => 'images',
            'icono2' => 'images',
            'icono3' => 'images',
        ];

        foreach ($archivos as $campo => $carpeta) {
            if ($request->hasFile($campo)) {

                // Borrar anterior
                if (!empty($nosotros->$campo)) {
                    $oldPath = str_replace('/storage/', '', $nosotros->$campo);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                // Subir nuevo archivo
                $file = $request->file($campo);
                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($carpeta, $name, 'public');

                $nosotros->$campo = Storage::url($path);
            }
        }

        // Guardar todo
        $nosotros->save();

        return redirect()
            ->route('adm.nosotros', ['nosotro_id' => $nosotros->id])
            ->with('success', 'Sección nosotros actualizada correctamente.');
    }
}