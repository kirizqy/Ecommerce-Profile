<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();    // Judul
            $table->text('description')->nullable(); // <— TAMBAH
            $table->string('image');                // Path gambar utama
            $table->string('media')->nullable();    // Video/file lainnya
            $table->string('type', 32)->default('image'); // <— TAMBAH (image|video|file)
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
