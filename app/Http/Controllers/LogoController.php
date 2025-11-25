<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function show($logos_id = null)
    {
        $logos = Logo::first() ?? new Logo();
        return view('content.admin.logo', ['logo' => $logos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'foto_nav' => 'nullable|image|max:2048', // 2 MB = 2048 KB
            'foto_nav2' => 'nullable|image|max:2048', // 2 MB = 2048 KB
            'foto_footer' => 'nullable|image|max:2048', // 2 MB = 2048 KB
        ]);

        $logos = Logo::find($request->logos_id) ?? new Logo();

        // Manejo de foto_nav
        if ($request->hasFile('foto_nav')) {
            if (!empty($logos->foto_nav)) {
                $oldPath = str_replace('/storage/', '', $logos->foto_nav);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto_nav');
            $name = time() . '.' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $logos->foto_nav = Storage::url($path);
        }

         // Manejo de foto_nav2
        if ($request->hasFile('foto_nav2')) {
            if (!empty($logos->foto_nav2)) {
                $oldPath = str_replace('/storage/', '', $logos->foto_nav2);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto_nav2');
            $name = time() . '.' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $logos->foto_nav2 = Storage::url($path);
        }

        // Manejo de foto_footer
        if ($request->hasFile('foto_footer')) {
            if (!empty($logos->foto_footer)) {
                $oldPath = str_replace('/storage/', '', $logos->foto_footer);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto_footer');
            $name = time() . '.' . $file->getClientOriginalName();
            $path = $file->storeAs('images', $name, 'public');
            $logos->foto_footer = Storage::url($path);
        }

        $logos->save();

        return redirect()->route('adm.logo', ['logo_id' => $logos->id])
            ->with('success', 'Logo actualizado correctamente.');
    }
}
