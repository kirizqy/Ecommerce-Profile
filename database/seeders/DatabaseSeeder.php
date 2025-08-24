<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,        // kalau masih mau pakai seeder lama
            AdminUsersSeeder::class,   // seeder baru (banyak admin)
        ]);
    }
}
