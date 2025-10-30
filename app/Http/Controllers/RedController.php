<?php

namespace App\Http\Controllers;

use App\Models\Red;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redes=Red::orderBy('orden')->get();
        return view('content.admin.redes',['red'=>$redes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.admin.redes-creador');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $redes= new Red();
        $redes->orden=$request->orden;
        $redes->nombre=$request->nombre;
        $redes->icono=$request->icono;

                // ✅ WhatsApp: genera URL completa
        if ($request->icono === 'fa-brands fa-whatsapp') {
            $numero = preg_replace('/[^0-9]/', '', $request->url);
            $redes->url = 'https://wa.me/' . $numero;
        }
        // ✅ Email: genera mailto automático
        elseif ($request->icono === 'fa-regular fa-envelope' || $request->icono === 'fa-solid fa-envelope') {
            $correo = trim($request->url);
            $redes->url = 'mailto:' . $correo;
        }
        // ✅ Otros: se guarda como viene
        else {
            $redes->url = $request->url;
        }

        $redes->save();
        return redirect()->route('adm.redes')->with('success', 'Red social creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($redes_id)
    {
               $redes=Red::find($redes_id);
        return view('content.admin.redes-editor' ,['red'=>$redes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $redes= Red::find($request->redes_id);
        
        if(!$redes){
            $redes=new Red();
        }
            $redes->orden=$request->orden;
            $redes->nombre=$request->nombre;
            $redes->icono=$request->icono;

                    // ✅ WhatsApp: genera URL completa
        if ($request->icono === 'fa-brands fa-whatsapp') {
            $numero = preg_replace('/[^0-9]/', '', $request->url);
            $redes->url = 'https://wa.me/' . $numero;
        }
        // ✅ Email: genera mailto automático
        elseif ($request->icono === 'fa-regular fa-envelope' || $request->icono === 'fa-solid fa-envelope') {
            $correo = trim($request->url);
            $redes->url = 'mailto:' . $correo;
        }
        // ✅ Otros: se guarda como viene
        else {
            $redes->url = $request->url;
        }

        $redes->save();
        return redirect()->route('adm.redes')->with('success', 'Red social actualizada correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($redes_id){
        $redes = Red::find($redes_id);
        $redes->delete();
        return redirect()->route('adm.redes')->with('success', 'Red social eliminada correctamente.');
    }
      

    public function switch($redes_id){
        $redes=Red::find($redes_id);
        $redes->active= !$redes->active;
        $redes->save();
        $status = $redes->active ? 'activada' : 'desactivada';
        return redirect()->route('adm.redes')->with('success', "Red social {$status} correctamente.");
    }
      

    public function reordenar(Request $request)
    {
        foreach ($request->orden as $redes) {
            \App\Models\Red::where('id', $redes['id'])->update(['orden' => $redes['orden']]);
        }

        return response()->json(['success' => true]);
    }  
}
