<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'binabordir@gmail.com'], // kunci unik
            [
                'name'              => 'Admin',
                'password'          => Hash::make('password123'),
                'is_admin'          => true,                 // <-- penting!
                'email_verified_at' => now(),               // opsional
                'remember_token'    => str()->random(10),   // opsional
            ]
        );
    }
}
