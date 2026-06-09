<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WebUsuarioAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web_usuario')->check()) {
            // Si es una petición AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado',
                    'redirect' => route('home')
                ], 401);
            }
            
            // Guardar la URL a la que intentaba acceder
            session(['intended_url' => $request->url()]);
            
            // Redirigir al home con parámetro para abrir modal
            return redirect()->route('home')->with('open_login_modal', true);
        }

        return $next($request);
    }
}