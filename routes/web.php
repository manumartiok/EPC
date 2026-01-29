<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\NosotroController;
use App\Http\Controllers\NosotroBannerController;
use App\Http\Controllers\NovedadeController;
use App\Http\Controllers\NovedadeBannerController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ContactoBannerController;

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\LogoController;
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

    //home banner
    Route::get('/admin/home/banner', [HomeBannerController::class,'index'])->name('adm.home-ban');
    Route::get('/admin/home/banner/creador', [HomeBannerController::class,'create'])->name('adm.home-ban-creador');
    Route::post('/admin/home/banner/store', [HomeBannerController::class,'store'])->name('adm.home-ban-store');
    Route::get('/admin/home/banner/editor/{home_banners_id}', [HomeBannerController::class,'show'])->name('adm.home-ban-editor');
    Route::post('/admin/home/banner/update', [HomeBannerController::class,'update'])->name('adm.home-ban-update');
    Route::get('/admin/home/banner/destroy/{home_banners_id}', [HomeBannerController::class,'destroy'])->name('adm.home-ban-destroy');
    Route::get('/admin/home/banner/switch/{home_banners_id}', [HomeBannerController::class,'switch'])->name('adm.home-ban-switch');
    Route::post('/admin/home-ban/reordenar', [HomeBannerController::class, 'reordenar'])->name('adm.home-ban-reordenar');

    //home 
    Route::get('/admin/home', [HomeController::class, 'show'])->name('adm.home');
    Route::post('/admin/home/update', [HomeController::class,'update'])->name('adm.home-update');

    //nosotros
    Route::get('/admin/nosotros', [NosotroController::class, 'show'])->name('adm.nosotros');
    Route::post('/admin/nosotros/update', [NosotroController::class,'update'])->name('adm.nosotros-update');

    //nosotros banner
    Route::get('/admin/nosotros/banner', [NosotroBannerController::class, 'show'])->name('adm.nosotros-ban');
    Route::post('/admin/nosotros/banner/update', [NosotroBannerController::class,'update'])->name('adm.nosotros-ban-update');

    //novedades
    Route::get('/admin/novedade', [NovedadeController::class, 'index'])->name('adm.novedade');
    Route::get('/admin/novedade/creador', [NovedadeController::class, 'create'])->name('adm.novedade-creador');
    Route::post('/admin/novedade/store', [NovedadeController::class, 'store'])->name('adm.novedade-store');
    Route::get('/admin/novedade/editor/{novedade_id}', [NovedadeController::class, 'show'])->name('adm.novedade-editor');
    Route::post('/admin/novedade/update', [NovedadeController::class, 'update'])->name('adm.novedade-update');
    Route::get('/admin/novedade/destroy/{novedade_id}', [NovedadeController::class, 'destroy'])->name('adm.novedade-destroy');
    Route::get('/admin/novedade/switch/{novedade_id}', [NovedadeController::class, 'switch'])->name('adm.novedade-switch');
    Route::get('/admin/novedade/destacado/{novedade_id}', [NovedadeController::class, 'destacado'])->name('adm.novedade-destacado');
    Route::post('/admin/novedade/reordenar', [NovedadeController::class, 'reordenar'])->name('adm.novedade-reordenar');

    //novedades banner
    Route::get('/admin/novedades/banner', [NovedadeBannerController::class, 'show'])->name('adm.novedade-ban');
    Route::post('/admin/novedades/banner/update', [NovedadeBannerController::class,'update'])->name('adm.novedade-ban-update');

    // contacto 
    Route::get('/admin/contacto', [ContactoController::class, 'show'])->name('adm.contacto');
    Route::post('/admin/contacto/update', [ContactoController::class, 'update'])->name('adm.contacto.update');

    // contacto banner 
    Route::get('/admin/contacto/banner', [ContactoBannerController::class, 'show'])->name('adm.contacto-ban');
    Route::post('/admin/contacto/banner/update', [ContactoBannerController::class, 'update'])->name('adm.contacto-ban.update');

    //newsletter
    Route::get('/admin/newsletter', [NewsletterController::class,'index'])->name('adm.newsletter');
    
    Route::post('/admin/newsletter/send', [NewsletterController::class,'send'])->name('adm.newsletter-send'); // NUEVA RUTA
    Route::get('/admin/newsletter/destroy/{newsletter_id}', [NewsletterController::class,'destroy'])->name('adm.newsletter-destroy');
    Route::get('/admin/newsletter/switch/{newsletter_id}', [NewsletterController::class,'switch'])->name('adm.newsletter-switch');
    Route::post('/admin/newsletter/reordenar', [NewsletterController::class, 'reordenar'])->name('adm.newsletter-reordenar');

    //logo
    Route::get('/admin/logo', [LogoController::class, 'show'])->name('adm.logo');
    Route::post('/admin/logo/update', [LogoController::class,'update'])->name('adm.logo-update');

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


Route::post('/admin/newsletter/store', [NewsletterController::class,'store'])->name('adm.newsletter-store');
//WEB
Route::get('/', function () {
    return view('content.web.home');})->name('home');

Route::get('/nosotros', function () {
    return view('content.web.nosotros');})->name('nosotros');

Route::get('/novedades', function () {
    return view('content.web.novedade');})->name('novedades');

Route::get('/novedades/detalle/{id}', [NovedadeController::class, 'detalle'])->name('novedades-detalle');

Route::get('/contacto', function () {
    return view('content.web.contacto');})->name('contacto'); 


Route::post('/contacto/enviar', [FormController::class, 'contacto'])->name('form.contacto');
