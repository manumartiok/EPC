<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class ProductoFoto extends Model
{
    protected $table = 'producto_fotos';

    protected $fillable = [
        'productos_id',
        'orden',
        'foto',
        'active',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }
}
