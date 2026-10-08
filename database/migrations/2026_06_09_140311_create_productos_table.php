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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('orden')->nullable();
            $table->string('nombre')->nullable();
            $table->string('nombre_completo')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('ficha_tecnica')->nullable();
            $table->text('aplicacion')->nullable();
            $table->text('certificado')->nullable();
            $table->json('caracteristicas')->nullable();
            $table->text('tamaños')->nullable();
            $table->json('colores')->nullable();
            $table->string('foto')->nullable();
            $table->string('archivo')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('destacado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
