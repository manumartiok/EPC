<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function show()
    {
        $home = Home::first() ?? new Home();

        return view('content.admin.home', compact('home'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'titulo1'            => 'nullable|string|max:255',
            'foto1'              => 'nullable|image|max:2048',
            'foto1_texto1'       => 'nullable|string|max:255',
            'foto1_texto2'       => 'nullable|string|max:255',
            'foto1_texto3'       => 'nullable|string|max:255',
            'foto1_texto_boton'  => 'nullable|string|max:255',
            'foto2'              => 'nullable|image|max:2048',
            'titulo2'            => 'nullable|string|max:255',
            'descripcion'        => 'nullable|string',
            'titulo3'            => 'nullable|string|max:255',
        ]);

        $home = Home::first() ?? new Home();

        $home->titulo1           = $request->titulo1;
        $home->foto1_texto1      = $request->foto1_texto1;
        $home->foto1_texto2      = $request->foto1_texto2;
        $home->foto1_texto3      = $request->foto1_texto3;
        $home->foto1_texto_boton = $request->foto1_texto_boton;
        $home->titulo2           = $request->titulo2;
        $home->descripcion       = $request->descripcion;
        $home->titulo3           = $request->titulo3;

        if ($request->hasFile('foto1')) {
            $this->handleImageUpload($home, $request->file('foto1'), 'foto1');
        }

        if ($request->hasFile('foto2')) {
            $this->handleImageUpload($home, $request->file('foto2'), 'foto2');
        }

        $home->save();

        return redirect()
            ->route('adm.home')
            ->with('success', 'Página de inicio actualizada correctamente.');
    }

    private function handleImageUpload(Home $home, $file, string $field)
    {
        if ($home->$field) {
            $oldPath = str_replace('/storage/', '', $home->$field);

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $name = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('images', $name, 'public');

        $home->$field = Storage::url($path);
    }
}