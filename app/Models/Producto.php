<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\ProductoFoto;

class Producto extends Model
{
    /**
     * Paleta de colores de aislación asignables a un producto.
     * 'estilo' es el valor del atributo CSS background de cada muestra.
     */
    public const COLORES = [
        'rojo'           => ['nombre' => 'Rojo',          'estilo' => '#FF0000'],
        'celeste'        => ['nombre' => 'Celeste',       'estilo' => '#00B0E8'],
        'verde-amarillo' => ['nombre' => 'Verde/Amarillo', 'estilo' => 'linear-gradient(to bottom, #00D300 0 40%, #FFCF00 40% 60%, #00D300 60% 100%)'],
        'marron'         => ['nombre' => 'Marrón',        'estilo' => '#B38B69'],
        'negro'          => ['nombre' => 'Negro',         'estilo' => '#222222'],
        'naranja'        => ['nombre' => 'Naranja',       'estilo' => '#FF873F'],
        'blanco'         => ['nombre' => 'Blanco',        'estilo' => '#FFFFFF'],
        'azul'           => ['nombre' => 'Azul',          'estilo' => '#0A3CC8'],
        'gris'           => ['nombre' => 'Gris',          'estilo' => '#9A9A9A'],
        'verde'          => ['nombre' => 'Verde',         'estilo' => '#00D300'],
    ];

    protected $fillable = [
        'orden',
        'nombre',
        'nombre_completo',
        'descripcion',
        'ficha_tecnica',
        'aplicacion',
        'certificado',
        'caracteristicas',
        'tamaños',
        'colores',
        'foto',
        'archivo',
        'active',
        'destacado',
    ];

    protected $casts = [
        'ficha_tecnica'   => 'array',   // [{nombre, valor}]
        'caracteristicas' => 'array',   // [{foto, titulo}]
        'colores'         => 'array',   // [{tipo, colores: [clave, ...]}]
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
