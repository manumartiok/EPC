<?php

namespace App\Http\Controllers;

use App\Models\NovedadeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadeBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show()
    {
        $novedade_banner = NovedadeBanner::first() ?? new NovedadeBanner();

        return view('content.admin.novedade-banner', [
            'novedade_banner' => $novedade_banner
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048', // 2 MB
        ]);

        $novedade_banner = NovedadeBanner::find($request->novedade_banners_id)
            ?? new NovedadeBanner();

        // Manejo de la imagen
        if ($request->hasFile('foto')) {

            if (!empty($novedade_banner->foto)) {
                $oldPath = str_replace('/storage/', '', $novedade_banner->foto);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');

            $novedade_banner->foto = Storage::url($path);
        }

        $novedade_banner->texto = $request->texto;
        $novedade_banner->save();

        return redirect()->route('adm.novedade-ban', [
            'novedade_banners_id' => $novedade_banner->id
        ])->with('success', 'Banner de novedades actualizado correctamente.');
    }
}
