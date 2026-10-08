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
        Schema::table('nosotros', function (Blueprint $table) {
            $table->string('titulo3')->nullable();
            $table->string('texto3')->nullable();
            $table->string('titulo4')->nullable();
            $table->string('texto4')->nullable();
            $table->string('jaula_titulo1')->nullable();
            $table->text('jaula_descripcion1')->nullable();
            $table->string('jaula_titulo2')->nullable();
            $table->text('jaula_descripcion2')->nullable();
            $table->string('jaula_titulo3')->nullable();
            $table->text('jaula_descripcion3')->nullable();
            $table->string('jaula_titulo4')->nullable();
            $table->text('jaula_descripcion4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nosotros', function (Blueprint $table) {
            $table->dropColumn([
                'titulo3', 'texto3', 'titulo4', 'texto4',
                'jaula_titulo1', 'jaula_descripcion1',
                'jaula_titulo2', 'jaula_descripcion2',
                'jaula_titulo3', 'jaula_descripcion3',
                'jaula_titulo4', 'jaula_descripcion4',
            ]);
        });
    }
};
