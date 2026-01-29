<?php

namespace App\Http\Controllers;

use App\Models\ContactoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactoBannerController extends Controller
{
    /**
     * Mostrar el banner de contacto (único registro)
     */
    public function show($contacto_banners_id = null)
    {
        $contacto_banner = ContactoBanner::first() ?? new ContactoBanner();

        return view('content.admin.contacto-banner', [
            'contacto_banner' => $contacto_banner
        ]);
    }

    /**
     * Actualizar o crear el banner de contacto
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048', // 2 MB
        ]);

        $contacto_banner = ContactoBanner::find($request->contacto_banners_id)
            ?? new ContactoBanner();

        // Manejo de la imagen
        if ($request->hasFile('foto')) {

            if (!empty($contacto_banner->foto)) {
                $oldPath = str_replace('/storage/', '', $contacto_banner->foto);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $contacto_banner->foto = Storage::url($path);
        }

        $contacto_banner->texto = $request->texto;
        $contacto_banner->save();

        return redirect()->route('adm.contacto-ban', [
            'contacto_banners_id' => $contacto_banner->id
        ])->with('success', 'Banner de contacto actualizado correctamente.');
    }
}
