<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi kategori (hapus produk jika kategori dihapus)
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->index();

            $table->string('name', 150);
            $table->text('description')->nullable();

            // Gambar
            $table->string('image')->nullable();   // simpan "products/xxx.jpg" (disk public)
            $table->json('images')->nullable();    // multi-gambar opsional

            // Link marketplace / kontak
            $table->string('shopee_link')->nullable();
            $table->string('tokopedia_link')->nullable();
            $table->string('whatsapp_link')->nullable();

            // Stok & harga
            $table->unsignedInteger('stock')->default(0)->index();
            $table->decimal('price', 12, 2)->default(0); // Rp aman sampai triliunan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
