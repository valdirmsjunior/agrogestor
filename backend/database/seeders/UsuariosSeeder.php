<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Usuario;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        Usuario::create([
            'name' => 'Administrador',
            'email' => 'admin@agro.com',
            'password' => Hash::make('1q2w3e'),
        ]);
    }
}
