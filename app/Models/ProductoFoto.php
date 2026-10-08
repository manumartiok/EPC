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

    public const EXTENSIONES_VIDEO = ['mp4', 'webm', 'ogg'];

    /**
     * La galería guarda fotos y videos en el mismo campo: se distinguen por la extensión.
     */
    public static function urlEsVideo(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: $url, PATHINFO_EXTENSION));

        return in_array($extension, self::EXTENSIONES_VIDEO, true);
    }

    public function getEsVideoAttribute(): bool
    {
        return self::urlEsVideo($this->foto);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }
}
