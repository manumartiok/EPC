<?php

namespace App\Http\Controllers;

use App\Models\NosotroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NosotroBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function show($nosotros_banners_id = null)
    {
        $nosotros_banners = NosotroBanner::first() ?? new NosotroBanner();
        return view('content.admin.nosotros-banner', ['nosotro_banners' => $nosotros_banners]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048', // 2 MB = 2048 KB
        ]);

        $nosotros_banners = NosotroBanner::find($request->nosotros_banners_id) ?? new NosotroBanner();

        // Manejo de la imagen
        if ($request->hasFile('foto')) {
            if (!empty($nosotros_banners->foto)) {
                $oldPath = str_replace('/storage/', '', $nosotros_banners->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '.' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $nosotros_banners->foto = Storage::url($path);
        }

        $nosotros_banners->texto = $request->texto;
        $nosotros_banners->save();

        return redirect()->route('adm.nosotros-ban', ['nosotro_banners_id' => $nosotros_banners->id])
            ->with('success', 'Banner de nosotros actualizado correctamente.');
    }
}
