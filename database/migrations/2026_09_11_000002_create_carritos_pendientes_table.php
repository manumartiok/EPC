<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carritos_pendientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('web_usuarios')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('web_usuarios')->nullOnDelete();
            $table->json('items');
            $table->timestamps();

            $table->unique(['usuario_id', 'cliente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carritos_pendientes');
    }
};
