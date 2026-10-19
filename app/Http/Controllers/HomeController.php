<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private const CAMPOS_TEXTO = [
        'texto_chico1', 'titulo1', 'descripcion',
        'texto_chico2', 'titulo2',
        'texto_chico3', 'titulo3', 'subtext3',
        'texto_chico4', 'titulo4',
        'texto5',
    ];

    public function show()
    {
        $home = Home::first() ?? new Home();

        return view('content.admin.home', compact('home'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'foto1'        => 'nullable|image|max:2048',
            'texto_chico1' => 'nullable|string|max:255',
            'titulo1'      => 'nullable|string|max:255',
            'descripcion'  => 'nullable|string',
            'texto_chico2' => 'nullable|string|max:255',
            'titulo2'      => 'nullable|string|max:255',
            'texto_chico3' => 'nullable|string|max:255',
            'titulo3'      => 'nullable|string|max:255',
            'subtext3'     => 'nullable|string|max:255',
            'texto_chico4' => 'nullable|string|max:255',
            'titulo4'      => 'nullable|string|max:255',
            'texto5'       => 'nullable|string|max:255',
        ]);

        $home = Home::first() ?? new Home();

        foreach (self::CAMPOS_TEXTO as $campo) {
            $home->$campo = $request->input($campo);
        }

        if ($request->hasFile('foto1')) {
            $this->handleImageUpload($home, $request->file('foto1'), 'foto1');
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
