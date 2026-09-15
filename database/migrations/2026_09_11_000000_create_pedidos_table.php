<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('web_usuarios')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('web_usuarios')->nullOnDelete();
            $table->string('estado')->default('en_preparacion');
            $table->string('entrega')->nullable();
            $table->string('forma_pago')->nullable();
            $table->text('mensaje')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('iva', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->json('totales')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
