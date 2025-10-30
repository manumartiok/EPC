<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PerfilController;

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\RedController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MetadatoController;
use App\Http\Controllers\FormController;

//ADMIN
Route::middleware(AdminAuth::class)->group(function () {

    //ADMIN
    Route::get('/admin/dashboard', function () {
    return view('content.admin.admin-dashboard');})->name('adm.dashboard');

    //perfil
    Route::get('/admin/perfil', [PerfilController::class, 'show'])->name('adm.perfil');
    Route::post('/admin/perfil/update', [PerfilController::class, 'update'])->name('adm.perfil-update');

    //newsletter
    Route::get('/admin/newsletter', [NewsletterController::class,'index'])->name('adm.newsletter');
    Route::post('/admin/newsletter/store', [NewsletterController::class,'store'])->name('adm.newsletter-store');
    Route::post('/admin/newsletter/send', [NewsletterController::class,'send'])->name('adm.newsletter-send'); // NUEVA RUTA
    Route::get('/admin/newsletter/destroy/{newsletter_id}', [NewsletterController::class,'destroy'])->name('adm.newsletter-destroy');
    Route::get('/admin/newsletter/switch/{newsletter_id}', [NewsletterController::class,'switch'])->name('adm.newsletter-switch');
    Route::post('/admin/newsletter/reordenar', [NewsletterController::class, 'reordenar'])->name('adm.newsletter-reordenar');


    //redes
    Route::get('/admin/redes', [RedController::class,'index'])->name('adm.redes');
    Route::get('/admin/redes/creador', [RedController::class,'create'])->name('adm.redes-creador');
    Route::post('/admin/redes/store', [RedController::class,'store'])->name('adm.redes-store');
    Route::get('/admin/redes/editor/{red_id}', [RedController::class,'show'])->name('adm.redes-editor');
    Route::post('/admin/redes/update', [RedController::class,'update'])->name('adm.redes-update');
    Route::get('/admin/redes/destroy/{red_id}', [RedController::class,'destroy'])->name('adm.redes-destroy');
    Route::get('/admin/redes/switch/{red_id}', [RedController::class,'switch'])->name('adm.redes-switch');
    Route::post('/admin/redes/reordenar', [RedController::class, 'reordenar'])->name('adm.redes-reordenar');

    //usuarios
    Route::get('/admin/usuarios', [UsuarioController::class,'index'])->name('adm.usuarios');
    Route::get('/admin/usuarios/creador', [UsuarioController::class,'create'])->name('adm.usuarios-creador');
    Route::post('/admin/usuarios/store', [UsuarioController::class,'store'])->name('adm.usuarios-store');
    Route::get('/admin/usuarios/editor/{usuario_id}', [UsuarioController::class,'show'])->name('adm.usuarios-editor');
    Route::post('/admin/usuarios/update', [UsuarioController::class,'update'])->name('adm.usuarios-update');
    Route::get('/admin/usuarios/destroy/{usuario_id}', [UsuarioController::class,'destroy'])->name('adm.usuarios-destroy');
    Route::get('/admin/usuarios/switch/{usuario_id}', [UsuarioController::class,'switch'])->name('adm.usuarios-switch');

    //metadatos
    Route::get('/admin/metadatos', [MetadatoController::class,'index'])->name('adm.metadatos');
    Route::get('/admin/metadatos/creador', [MetadatoController::class,'create'])->name('adm.metadatos-creador');
    Route::post('/admin/metadatos/store', [MetadatoController::class,'store'])->name('adm.metadatos-store');
    Route::get('/admin/metadatos/editor/{metadato_id}', [MetadatoController::class,'show'])->name('adm.metadatos-editor');
    Route::post('/admin/metadatos/update', [MetadatoController::class,'update'])->name('adm.metadatos-update');
    Route::get('/admin/metadatos/destroy/{metadato_id}', [MetadatoController::class,'destroy'])->name('adm.metadatos-destroy');
});


//login
Route::get('/admin/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class,'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class,'logout'])->name('logout');
Route::post('/admin/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');



//WEB
Route::get('/', function () {
    return view('content.web.home');})->name('home');


Route::post('/contacto/enviar', [FormController::class, 'contacto'])->name('form.contacto');
