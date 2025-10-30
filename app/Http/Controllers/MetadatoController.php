<?php

namespace App\Http\Controllers;

use App\Models\Metadato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetadatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metadatos=Metadato::all();
        return view('content.admin.metadatos',['metadato'=>$metadatos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.admin.metadatos-creador');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $metadatos= new Metadato();
        $metadatos->seccion=$request->seccion;
        $metadatos->keywords=$request->keywords;
        $metadatos->descripcion=$request->descripcion;

        $metadatos->save();
        return redirect()->route('adm.metadatos')->with('success', 'Metadato creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($metadatos_id)
    {
               $metadatos=Metadato::find($metadatos_id);
        return view('content.admin.metadatos-editor' ,['metadato'=>$metadatos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $metadatos= Metadato::find($request->metadatos_id);
        
        if(!$metadatos){
            $metadatos=new Metadato();
        }
            $metadatos->seccion=$request->seccion;
            $metadatos->keywords=$request->keywords;
            $metadatos->descripcion=$request->descripcion;

        $metadatos->save();
        return redirect()->route('adm.metadatos')->with('success', 'Metadato actualizado correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($metadatos_id)
    {
        $metadatos = Metadato::find($metadatos_id);
        $metadatos->delete();
        return redirect()->route('adm.metadatos')->with('success', 'Metadato eliminado correctamente.');
    }
}
