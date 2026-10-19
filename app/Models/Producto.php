<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
        'caracteristicas_tecnicas',
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

    /**
     * Secciones disponibles del producto, tomadas de la tabla de "características técnicas".
     * Se usa toda columna cuyo título contenga "sección" (ej: "Sección nominal"), sin importar
     * mayúsculas ni tildes. La 1ª fila son los títulos y la 2ª las unidades (MM2), el resto son datos.
     *
     * @return string[]
     */
    public function getSeccionesAttribute(): array
    {
        $html = $this->caracteristicas_tecnicas;
        if (empty($html)) {
            return [];
        }

        $previo = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<meta charset="utf-8">' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previo);

        $xpath = new \DOMXPath($dom);
        $limpiar = fn (string $t) => trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $t));
        $secciones = [];

        foreach ($xpath->query('//table') as $tabla) {
            $filas = $xpath->query('.//tr', $tabla);
            if ($filas->length < 3) {
                continue;
            }

            $columnas = [];
            foreach ($xpath->query('./td|./th', $filas->item(0)) as $i => $celda) {
                if (str_contains(strtolower(Str::ascii($celda->textContent)), 'seccion')) {
                    $columnas[] = $i;
                }
            }

            for ($f = 2; $f < $filas->length; $f++) {
                $celdas = $xpath->query('./td|./th', $filas->item($f));
                foreach ($columnas as $c) {
                    $valor = $limpiar($celdas->item($c)?->textContent ?? '');
                    if ($valor !== '') {
                        $secciones[] = $valor;
                    }
                }
            }
        }

        return array_values(array_unique($secciones));
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function fotos()
    {
        return $this->hasMany(ProductoFoto::class, 'productos_id')->orderBy('orden');
    }
}
