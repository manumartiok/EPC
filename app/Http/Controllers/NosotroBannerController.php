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
   public function show()
    {
        $bn = NosotroBanner::first() ?? new NosotroBanner();
        return view('content.admin.nosotros-banner', ['bn' => $bn]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048', // 2 MB = 2048 KB
        ]);

        $bn = NosotroBanner::find($request->bn_id) ?? new NosotroBanner();

        // Manejo de la imagen
        if ($request->hasFile('foto')) {
            if (!empty($bn->foto)) {
                $oldPath = str_replace('/storage/', '', $bn->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = time() . '.' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $bn->foto = Storage::url($path);
        }

        $bn->texto = $request->texto;
        $bn->save();

        return redirect()->route('adm.nosotros-ban', ['bn_id' => $bn->id])
            ->with('success', 'Banner de nosotros actualizado correctamente.');
    }
}
