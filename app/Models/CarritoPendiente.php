<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoPendiente extends Model
{
    protected $table = 'carritos_pendientes';

    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
