<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InfoPagoMail;

class InfoPagoController extends Controller
{
    public function enviar(Request $request)
    {
        // Validación básica
        $request->validate([
            'fecha' => 'required|date',
            'importe' => 'required|numeric',
            'banco' => 'required|string',
            'sucursal' => 'required|string',
            'archivo' => 'required|file',
        ]);

        Mail::to('test@local.com')->send(
            new InfoPagoMail(
                $request->except('archivo'),
                $request->file('archivo')
            )
        );


        return back()->with('success', 'Información enviada correctamente');
    }
}
