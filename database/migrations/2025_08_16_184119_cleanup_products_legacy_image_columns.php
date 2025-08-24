<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Pastikan kolom 'image' & 'images' ada
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('stock');
            }
            if (!Schema::hasColumn('products', 'images')) {
                // Jika MySQL/MariaDB lama, boleh ganti json() -> text()
                $table->json('images')->nullable()->after('image');
            }
        });

        // Kolom legacy yang mau kita gabungkan, hanya yang benar-benar ada di DB
        $legacy = collect(['image2','image3','image4'])
            ->filter(fn($c) => Schema::hasColumn('products', $c))
            ->values();

        if ($legacy->isNotEmpty()) {
            // Normalizer path sederhana
            $norm = function (?string $raw): ?string {
                $raw = trim((string) $raw);
                if ($raw === '') return null;
                $raw = str_replace('\\', '/', $raw);
                $raw = preg_replace('#^(public/|storage/)#', '', $raw);
                $raw = ltrim($raw, '/');
                return preg_replace('#/{2,}#', '/', $raw);
            };

            // Backfill: pindahkan image2..4 ke images JSON
            DB::table('products')
                ->select(array_merge(['id','image','images'], $legacy->all()))
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($legacy, $norm) {
                    foreach ($rows as $row) {
                        // Decode images JSON lama (kalau ada)
                        $jsonArr = [];
                        if (!empty($row->images)) {
                            $decoded = json_decode($row->images, true);
                            if (is_array($decoded)) $jsonArr = $decoded;
                        }

                        // Kumpulkan semua kandidat path: cover lama + JSON lama + legacy cols
                        $candidates = [];
                        $coverCurrent = $norm($row->image);
                        if ($coverCurrent) $candidates[] = $coverCurrent;

                        foreach ($jsonArr as $p) {
                            $p = $norm($p);
                            if ($p) $candidates[] = $p;
                        }
                        foreach ($legacy as $col) {
                            $p = $norm($row->{$col} ?? null);
                            if ($p) $candidates[] = $p;
                        }

                        // Unik & reindex
                        $candidates = array_values(array_unique($candidates));

                        if (empty($candidates)) {
                            // Tidak ada apa-apa: kosongkan images JSON saja, cover biarkan apa adanya
                            DB::table('products')->where('id', $row->id)->update([
                                'images' => json_encode([]),
                            ]);
                            continue;
                        }

                        // Tentukan cover: pakai image lama jika ada, kalau tidak pakai elemen pertama
                        $cover = $coverCurrent ?: $candidates[0];

                        // JSON = semua selain cover
                        $json = array_values(array_filter($candidates, fn($p) => $p !== $cover));

                        DB::table('products')->where('id', $row->id)->update([
                            'image'  => $cover,
                            'images' => json_encode($json),
                        ]);
                    }
                });

            // Hapus kolom legacy
            Schema::table('products', function (Blueprint $table) use ($legacy) {
                $table->dropColumn($legacy->all());
            });
        }
    }

    public function down(): void
    {
        // Kembalikan kolom legacy kosong (tanpa backfill)
        Schema::table('products', function (Blueprint $table) {
            foreach (['image2','image3','image4'] as $col) {
                if (!Schema::hasColumn('products', $col)) {
                    $table->string($col)->nullable()->after('images');
                }
            }
        });
        // (opsional) tidak perlu mengubah 'image' dan 'images' saat rollback
    }
};
