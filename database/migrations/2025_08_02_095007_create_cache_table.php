<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom category_id jika belum ada
        if (!Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->after('id');
            });
        }

        // Tambah foreign key constraint jika belum ada
        try {
            DB::statement('
                ALTER TABLE products 
                ADD CONSTRAINT fk_products_category 
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            ');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek apakah error karena constraint sudah ada
            if (str_contains($e->getMessage(), 'errno: 121')) {
                // Constraint sudah ada, abaikan
                info('Constraint fk_products_category sudah ada, skip.');
            } else {
                // Jika bukan error karena duplicate key, lempar ulang
                throw $e;
            }
        }
    }

    public function down()
    {
        // Drop foreign key constraint (jika ada)
        Schema::table('products', function (Blueprint $table) {
            try {
                $table->dropForeign('fk_products_category');
            } catch (\Illuminate\Database\QueryException $e) {
                // Constraint tidak ada atau sudah terhapus — bisa diabaikan
                info('Constraint fk_products_category tidak ditemukan saat rollback.');
            }

            // Drop kolom jika masih ada
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }
};
