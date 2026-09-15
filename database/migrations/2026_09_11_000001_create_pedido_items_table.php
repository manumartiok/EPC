<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->string('seccion')->nullable();
            $table->string('formacion')->nullable();
            $table->string('color')->nullable();
            $table->string('numerado')->nullable();
            $table->string('presentacion')->nullable();
            $table->decimal('cantidad_metros', 14, 2)->default(0);
            $table->decimal('rollos', 14, 2)->default(0);
            $table->decimal('bobinas', 14, 2)->default(0);
            $table->decimal('metros', 14, 2)->default(0);
            $table->decimal('precio_base', 14, 2)->default(0);
            $table->decimal('precio_con_descuento', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->json('carrito_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
