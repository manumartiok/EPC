<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'estado',
        'entrega',
        'forma_pago',
        'mensaje',
        'fecha_entrega',
        'subtotal',
        'iva',
        'total',
        'totales',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'iva' => 'float',
        'total' => 'float',
        'totales' => 'array',
        'fecha_entrega' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(WebUsuario::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(WebUsuario::class, 'cliente_id');
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
