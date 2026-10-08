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
        Schema::create('catalogos', function (Blueprint $table) {
            $table->id();

            // encabezado
            $table->string('titulo1')->nullable();
            $table->string('subtitulo1')->nullable();
            $table->text('texto')->nullable();

            // catálogo completo (bloque central)
            $table->string('foto')->nullable();
            $table->string('archivo')->nullable();
            $table->string('titulo_archivo')->nullable();
            $table->string('subtitulo_archivo')->nullable();
            $table->text('descripcion_archivo')->nullable();
            $table->unsignedInteger('paginas')->nullable();
            $table->unsignedBigInteger('peso')->nullable(); // bytes
            $table->date('fecha_archivo')->nullable();

            // por categoría
            $table->string('titulo2')->nullable();
            $table->string('subtitulo2')->nullable();
            $table->json('categorias')->nullable(); // [{nombre, archivo}]

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogos');
    }
};
