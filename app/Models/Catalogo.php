<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $casts = [
        'categorias'    => 'array',
        'fecha_archivo' => 'date',
    ];

    /**
     * Peso del archivo legible (ej: "18 MB"), sin depender de la extensión intl.
     */
    public function pesoFormateado(int $decimales = 0): ?string
    {
        if (!$this->peso) {
            return null;
        }

        $unidades = ['B', 'KB', 'MB', 'GB'];
        $valor = (float) $this->peso;
        $i = 0;

        while ($valor >= 1024 && $i < count($unidades) - 1) {
            $valor /= 1024;
            $i++;
        }

        return number_format($valor, $i === 0 ? 0 : $decimales, ',', '.') . ' ' . $unidades[$i];
    }
}
