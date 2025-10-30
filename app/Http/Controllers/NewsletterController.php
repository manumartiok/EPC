<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Mostrar listado de correos suscritos.
     */
    public function index()
    {
        $newsletters = Newsletter::orderBy('orden')->get();
        return view('content.admin.newsletter', ['newsletter' => $newsletters]);
    }

    /**
     * Guardar nuevo correo.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:newsletters,email'
            ]);

            // Obtener el último orden en formato texto
            $lastNewsletter = Newsletter::orderBy('orden', 'desc')->first();
            
            // Generar el siguiente orden en formato AA, BB, CC...
            if ($lastNewsletter && $lastNewsletter->orden) {
                $lastOrden = $lastNewsletter->orden;
                // Incrementar la letra (AA -> BB -> CC)
                $newsletter_orden = ++$lastOrden;
            } else {
                // Si no hay registros, empezar con AA
                $newsletter_orden = 'AA';
            }

            $newsletter = new Newsletter();
            $newsletter->email = $request->email;
            $newsletter->orden = $newsletter_orden;
            $newsletter->active = 1; // Activar por defecto
            $newsletter->save();

            return response()->json([
                'success' => true,
                'message' => '¡Gracias por suscribirte! Te mantendremos informado.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El email ya está registrado o no es válido.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error. Por favor, intenta nuevamente.'
            ], 500);
        }
    }

    /**
     * Eliminar correo.
     */
    public function destroy($newsletter_id)
    {
        $newsletter = Newsletter::find($newsletter_id);
        if ($newsletter) {
            $newsletter->delete();
            return redirect()->route('adm.newsletter')->with('success', 'Suscriptor eliminado correctamente.');
        }
        return redirect()->route('adm.newsletter')->with('error', 'No se encontró el suscriptor.');
    }

    /**
     * Activar o desactivar un correo.
     */
    public function switch($newsletter_id)
    {
        $newsletter = Newsletter::find($newsletter_id);
        if ($newsletter) {
            $newsletter->active = !$newsletter->active;
            $newsletter->save();
            $status = $newsletter->active ? 'activado' : 'desactivado';
            return redirect()->route('adm.newsletter')->with('success', "Suscriptor {$status} correctamente.");
        }
        return redirect()->route('adm.newsletter')->with('error', 'No se encontró el suscriptor.');
    }

    /**
     * Reordenar lista de correos.
     */
    public function reordenar(Request $request)
    {
        foreach ($request->orden as $item) {
            Newsletter::where('id', $item['id'])->update(['orden' => $item['orden']]);
        }

        return response()->json(['success' => true]);
    }

    //      * Enviar newsletter a todos los suscriptores activos.
    //  */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'asunto' => 'required|string|max:255',
                'mensaje' => 'required|string'
            ]);

            // Obtener todos los emails activos
            $suscriptores = Newsletter::where('active', 1)->pluck('email')->toArray();

            if (empty($suscriptores)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suscriptores activos.'
                ], 400);
            }

            $asunto = $request->asunto;
            $mensaje = $request->mensaje;
            

            // Enviar email a cada suscriptor
            foreach ($suscriptores as $email) {
                Mail::send([], [], function ($message) use ($email, $asunto, $mensaje) {
                    $message->to($email)
                            ->subject($asunto)
                            ->html($mensaje);
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Newsletter enviado exitosamente a ' . count($suscriptores) . ' suscriptores.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el newsletter: ' . $e->getMessage()
            ], 500);
        }
    }
}
