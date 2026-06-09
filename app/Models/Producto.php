<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\ProductoFoto;

class Producto extends Model
{
    protected $fillable = [
        'marca_id',
        'orden',
        'nombre',
        'codigo',
        'precio',
        'foto',
        'archivo',
        'active',
        'destacado',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function fotos()
    {
        return $this->hasMany(ProductoFoto::class, 'productos_id')->orderBy('orden');
    }
}
