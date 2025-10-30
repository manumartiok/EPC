<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios=Usuario::all();
        return view('content.admin.usuarios',['usuario'=>$usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.admin.usuarios-creador');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuarios= new Usuario();
        $usuarios->usuario=$request->usuario;
        $usuarios->email=$request->email;
        $usuarios->contraseña = Hash::make($request->contraseña);
        $usuarios->rol=$request->rol;

        $usuarios->save();
        return redirect()->route('adm.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($usuarios_id)
    {
               $usuarios=Usuario::find($usuarios_id);
        return view('content.admin.usuarios-editor' ,['usuario'=>$usuarios]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $usuarios= Usuario::find($request->usuarios_id);
        
        if(!$usuarios){
            $usuarios=new Usuario();
        }
            $usuarios->usuario=$request->usuario;
            $usuarios->email=$request->email;
            $usuarios->rol=$request->rol;

            // ✅ Si el usuario escribió una nueva contraseña
        if (!empty($request->contraseña)) {
            // Validar que coincidan
            if ($request->contraseña !== $request->password_confirmation) {
                return back()->withErrors(['contraseña' => 'Las contraseñas no coinciden.'])->withInput();
            }

            // Encriptar y guardar nueva contraseña
            $usuarios->contraseña = Hash::make($request->contraseña);
        }

        $usuarios->save();
        return redirect()->route('adm.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($usuarios_id)
    {
        $usuarios = Usuario::find($usuarios_id);
        $usuarios->delete();
        return redirect()->route('adm.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
      

    public function switch($usuarios_id)
    {
        $usuarios=Usuario::find($usuarios_id);
        $usuarios->active= !$usuarios->active;
        $usuarios->save();
        $status = $usuarios->active ? 'activado' : 'desactivado';
        return redirect()->route('adm.usuarios')->with('success', "Usuario {$status} correctamente.");
    }
}
