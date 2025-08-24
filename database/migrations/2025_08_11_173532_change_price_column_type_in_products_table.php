<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ubah price jadi BIGINT unsigned tanpa koma
            $table->unsignedBigInteger('price')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Balikin ke DECIMAL(10,2) kalau rollback
            $table->decimal('price', 10, 2)->change();
        });
    }
};
