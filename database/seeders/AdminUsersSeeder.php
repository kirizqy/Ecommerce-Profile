<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ EDIT DAFTAR ADMIN DI SINI
        $admins = [
            ['name' => 'Owner',      'email' => 'ownerbinabordir@gmail.com',      'password' => 'Owner#2025'],
            ['name' => 'Supervisor', 'email' => 'supervisorbinabordir@gmail.com', 'password' => 'spv#2025'],
            ['name' => 'Staff 1',    'email' => 'staff1binabordir@gmail.com',     'password' => 'Staff1#2025'],
        ];

        foreach ($admins as $a) {
            $pwdPlain = $a['password'] ?: Str::random(12);

            // Jika user sudah ada: update name + is_admin.
            // Password hanya diubah jika kamu sediakan di array (tidak null),
            // atau user-nya belum ada (baru dibuat).
            $user = User::firstOrNew(['email' => $a['email']]);
            $user->name = $a['name'];
            $user->is_admin = true;

            if (! $user->exists || $a['password']) {
                $user->password = Hash::make($pwdPlain);
            }

            $user->save();

            if (! $user->wasRecentlyCreated && ! $a['password']) {
                $this->command?->info("✔ {$a['email']} dipromosikan/diupdate (password TIDAK diubah).");
            } else {
                $this->command?->warn("★ {$a['email']} dibuat/diupdate. Password: {$pwdPlain}");
            }
        }
    }
}
