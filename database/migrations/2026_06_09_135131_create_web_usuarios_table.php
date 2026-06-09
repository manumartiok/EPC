<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario')->nullable();
            $table->string('email')->nullable();
            $table->string('contraseña');
            $table->string('descuento1')->nullable();
            $table->string('descuento2')->nullable();
            $table->string('descuento3')->nullable();
            $table->string('descuento4')->nullable();
            $table->string('descuento5')->nullable();
            $table->string('razon_social')->nullable();
             $table->string('empresa')->nullable();
        $table->string('cuit')->nullable();
        $table->string('telefono')->nullable();
        $table->string('domicilio')->nullable();
        $table->string('codigo_postal')->nullable();
        $table->string('localidad')->nullable();
        $table->string('provincia')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_usuarios');
    }
};
