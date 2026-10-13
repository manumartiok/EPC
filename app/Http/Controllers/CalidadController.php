<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalidadController extends Controller
{
    /**
     * Mostrar el recurso.
     */
    public function show()
    {
        $calidad = Calidad::first() ?? new Calidad();

        return view('content.admin.calidad', [
            'calidad'      => $calidad,
            'certificados' => Certificado::orderBy('orden')->get(),
        ]);
    }

    /**
     * Actualizar o crear el recurso (incluye la tabla de certificados).
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto'                   => 'nullable|image|max:2048',
            'certificados'           => 'nullable|array',
            'certificados.*.foto'    => 'nullable|image|max:2048',
            // Imagen (se muestra y se amplía) o documento (se descarga)
            'certificados.*.archivo' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,zip|max:20480',
        ]);

        $calidad = Calidad::find($request->calidad_id) ?? new Calidad();

        $calidad->subtitulo   = $request->input('subtitulo');
        $calidad->titulo      = $request->input('titulo');
        $calidad->texto       = $request->input('texto');
        $calidad->descripcion = $request->input('descripcion');

        if ($request->hasFile('foto')) {
            $this->borrarArchivo($calidad->foto);
            $calidad->foto = $this->subirArchivo($request->file('foto'), 'images');
        }

        $calidad->save();

        $this->guardarCertificados($request);

        return redirect()->route('adm.calidad')
            ->with('success', 'Calidad actualizada correctamente.');
    }

    /**
     * Certificados: cada fila de la tabla es un registro; el orden es el de las filas.
     * Los certificados que ya no vienen en la tabla se borran junto con sus archivos.
     */
    private function guardarCertificados(Request $request): void
    {
        $conservados = [];

        foreach ($request->input('certificados', []) as $i => $fila) {
            $certificado = !empty($fila['id']) ? Certificado::find($fila['id']) : null;
            $nombre      = trim($fila['nombre'] ?? '');

            $trae = $request->hasFile("certificados.$i.foto") || $request->hasFile("certificados.$i.archivo");

            // Fila vacía: no se crea
            if (!$certificado && $nombre === '' && !$trae) {
                continue;
            }

            $certificado = $certificado ?? new Certificado();

            $certificado->orden  = count($conservados) + 1;
            $certificado->nombre = $nombre;
            $certificado->active = !empty($fila['active']);

            if ($request->hasFile("certificados.$i.foto")) {
                $this->borrarArchivo($certificado->foto);
                $certificado->foto = $this->subirArchivo($request->file("certificados.$i.foto"), 'images');
            }

            if ($request->hasFile("certificados.$i.archivo")) {
                $file = $request->file("certificados.$i.archivo");

                $this->borrarArchivo($certificado->archivo);
                $certificado->peso    = $file->getSize();
                $certificado->archivo = $this->subirArchivo($file, 'certificados');
            }

            $certificado->save();
            $conservados[] = $certificado->id;
        }

        foreach (Certificado::whereNotIn('id', $conservados)->get() as $quitado) {
            $this->borrarArchivo($quitado->foto);
            $this->borrarArchivo($quitado->archivo);
            $quitado->delete();
        }
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
