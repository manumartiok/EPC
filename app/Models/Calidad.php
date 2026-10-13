<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calidad extends Model
{
    protected $table = 'calidads';

    protected $fillable = [
        'subtitulo',
        'titulo',
        'texto',
        'foto',
        'descripcion',
    ];
}
