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
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('foto1')->nullable();
            $table->string('texto_chico1')->nullable();
            $table->string('titulo1')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('texto_chico2')->nullable();
            $table->string('titulo2')->nullable();
            $table->string('texto_chico3')->nullable();
            $table->string('titulo3')->nullable();
            $table->string('subtext3')->nullable();
            $table->string('texto_chico4')->nullable();
            $table->string('titulo4')->nullable();
            $table->string('texto5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homes');
    }
};
