<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Kaos Bordir',
            'description' => 'Kaos bordir custom logo komunitas/organisasi.',
            'image' => 'produk1.jpg',
        ]);

        Product::create([
            'name' => 'Topi Bordir',
            'description' => 'Topi bordir keren untuk acara atau souvenir.',
            'image' => 'produk2.jpg',
        ]);
    }
}
