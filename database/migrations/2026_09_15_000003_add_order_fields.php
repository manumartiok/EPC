<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pedidos', 'fecha_entrega')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->date('fecha_entrega')->nullable()->after('mensaje');
            });
        }

        if (!Schema::hasColumn('pedido_items', 'cantidad')) {
            Schema::table('pedido_items', function (Blueprint $table) {
                $table->decimal('cantidad', 14, 2)->default(0)->after('presentacion');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pedidos', 'fecha_entrega')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('fecha_entrega');
            });
        }

        if (Schema::hasColumn('pedido_items', 'cantidad')) {
            Schema::table('pedido_items', function (Blueprint $table) {
                $table->dropColumn('cantidad');
            });
        }
    }
};
