<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\WebUsuario;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Usuario::create([
            'usuario' => 'pablo',
            'contraseña' => Hash::make('pablopablo'),
            'rol' => 'Administrador',
        ]);

        WebUsuario::create([
            'usuario' => 'prueba',
            'contraseña' => Hash::make('12345678'),
            'email' => 'prueba@prueba.com',
            'descuento1' => 10,
            'active' => true,
        ]);

        Marca::create([
            'nombre' => 'prueba',
            'active' => true,
        ]);

        Producto::create([
            'marca_id' => 1,
            'nombre' => 'prueba',
            'codigo' => '1ab',
            'precio' => '1000',
            'active' => true,
        ]);
    }
}
