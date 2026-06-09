<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\WebUsuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin(Request $request)
    {
        // Si venimos de un logout exitoso, podemos mostrar un mensaje
        $logoutMessage = session('logout_message');
        return view('auth.login', compact('logoutMessage'));
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contraseña' => 'required',
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)
                          ->orWhere('email', $request->usuario)
                          ->first();

        if (!$usuario) {
            return back()->withErrors([
                'usuario' => 'Usuario o email incorrecto',
            ]);
        }

        if (!Hash::check($request->contraseña, $usuario->contraseña)) {
            return back()->withErrors([
                'usuario' => 'Contraseña incorrecta',
            ]);
        }

        if (!$usuario->active) {
            return back()->withErrors([
                'usuario' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ]);
        }

        // Login exitoso
        Auth::login($usuario);
        return redirect()->route('adm.dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir al login con mensaje
        return redirect('/admin/login')->with('logout_message', 'Has cerrado sesión correctamente.');
    }

    // Forgot password
    public function forgotPassword(Request $request)
    {
        // Validación básica
        $request->validate([
            'email' => 'required|email|exists:usuarios,email', // ajustado a tu tabla
        ]);

        // Enviar link de reseteo
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        } else {
            return back()->with('error', __($status));
        }
    }

    // ============================================
    // WEB USUARIOS (ZONA PRIVADA)
    // ============================================
    
    public function webLogin(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contraseña' => 'required',
        ]);

        $usuario = WebUsuario::where('usuario', $request->usuario)
                         ->orWhere('email', $request->usuario)  // ← agregar esto
                         ->first();
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario incorrecto'
            ], 422);
        }

        if (!Hash::check($request->contraseña, $usuario->contraseña)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta'
            ], 422);
        }

        if (!$usuario->active) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está inactiva. Contacta al administrador.'
            ], 422);
        }

        // Login exitoso con guard específico
        Auth::guard('web_usuario')->login($usuario);
        $request->session()->regenerate();

            // Obtener la URL a la que intentaba acceder o usar ruta por defecto
    $intendedUrl = session('intended_url', route('web-productos'));
    session()->forget('intended_url'); // Limpiar la sesión

    return response()->json([
        'success' => true,
        'message' => 'Login exitoso',
        'redirect' => $intendedUrl
    ]);

    }

    public function webRegister(Request $request)
    {
        $validated = $request->validate([
            'usuario' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:web_usuarios'],
            'contraseña' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'usuario.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'contraseña.required' => 'La contraseña es obligatoria',
            'contraseña.confirmed' => 'Las contraseñas no coinciden',
            'contraseña.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);
        
        $usuario = WebUsuario::create([
            'usuario'      => $validated['usuario'],
            'email'        => $validated['email'],
            'contraseña'   => Hash::make($validated['contraseña']),
            // 'rol'          => null,
            'empresa'      => $request->empresa,
            'cuit'         => $request->cuit,
            'telefono'     => $request->telefono,
            'domicilio'    => $request->domicilio,
            'codigo_postal'=> $request->codigo_postal,
            'localidad'    => $request->localidad,
            'provincia'    => $request->provincia,
            // 'pais'         => $request->pais,   
        ]);


        // Después de crear el usuario, antes del return
try {
    $cuerpo = "
        <h2>Nuevo registro de usuario</h2>
        <p><strong>Usuario:</strong> {$usuario->usuario}</p>
        <p><strong>Email:</strong> {$usuario->email}</p>
        <p><strong>Empresa:</strong> {$request->empresa}</p>
        <p><strong>CUIT:</strong> {$request->cuit}</p>
        <p><strong>Teléfono:</strong> {$request->telefono}</p>
        <p><strong>Domicilio:</strong> {$request->domicilio}</p>
        <p><strong>Localidad:</strong> {$request->localidad}, {$request->provincia}, {$request->pais}</p>
        <p><strong>CP:</strong> {$request->codigo_postal}</p>
    ";

    Mail::html($cuerpo, function ($m) {
        $m->to('martimanuel912@gmail.com')
          ->subject('Nuevo usuario registrado');
    });
} catch (\Exception $e) {
    Log::error('Error mail nuevo usuario: ' . $e->getMessage());
}

        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso, esperar confirmacion del administrador',
            'redirect' => route('home')
        ]);
    }

    public function webLogout(Request $request)
    {
        Auth::guard('web_usuario')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('message', 'Has cerrado sesión correctamente');
    }
}