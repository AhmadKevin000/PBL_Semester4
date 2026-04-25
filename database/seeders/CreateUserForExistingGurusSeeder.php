<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserForExistingGurusSeeder extends Seeder
{
    /**
     * Membuat akun User untuk semua guru yang belum memiliki user_id.
     * Password default: "password" — admin wajib menginformasikan ke guru.
     */
    public function run(): void
    {
        $gurus = Guru::whereNull('user_id')->get();

        $this->command->info("Ditemukan {$gurus->count()} guru tanpa akun. Memproses...");

        foreach ($gurus as $guru) {
            // Cek apakah email sudah terdaftar di tabel users
            $existingUser = User::where('email', $guru->email)->first();

            if ($existingUser) {
                // Jika email sudah ada, hubungkan saja
                if ($existingUser->role !== 'guru') {
                    $existingUser->update(['role' => 'guru']);
                }
                $guru->update(['user_id' => $existingUser->id]);
                $this->command->warn("  [SKIP] {$guru->nama} ({$guru->email}) — email sudah terdaftar, dihubungkan ke user yang ada.");
                continue;
            }

            // Buat user baru
            $user = User::create([
                'name'     => $guru->nama,
                'email'    => $guru->email,
                'password' => Hash::make('password'),
                'role'     => 'guru',
            ]);

            $guru->update(['user_id' => $user->id]);

            $this->command->info("  [OK] {$guru->nama} ({$guru->email}) — akun berhasil dibuat.");
        }

        $this->command->info('Selesai. Password default semua guru baru: "password"');
        $this->command->warn('PENTING: Minta guru untuk mengganti password setelah login pertama!');
    }
}
