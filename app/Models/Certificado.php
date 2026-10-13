<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    public const EXTENSIONES_IMAGEN = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    protected $fillable = [
        'orden',
        'nombre',
        'foto',
        'archivo',
        'peso',
        'active',
    ];

    public function extension(): ?string
    {
        return $this->archivo
            ? strtolower(pathinfo(parse_url($this->archivo, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION))
            : null;
    }

    /**
     * Si el archivo es una imagen se muestra la foto (y se amplía); si no, se ofrece la descarga.
     */
    public function archivoEsImagen(): bool
    {
        return in_array($this->extension(), self::EXTENSIONES_IMAGEN, true);
    }

    /**
     * Peso del archivo legible (ej: "205kb").
     */
    public function pesoFormateado(): ?string
    {
        if (!$this->peso) {
            return null;
        }

        $unidades = ['b', 'kb', 'mb', 'gb'];
        $valor = (float) $this->peso;
        $i = 0;

        while ($valor >= 1024 && $i < count($unidades) - 1) {
            $valor /= 1024;
            $i++;
        }

        return number_format($valor, $i <= 1 ? 0 : 1, ',', '.') . $unidades[$i];
    }
}
