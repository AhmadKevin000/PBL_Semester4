<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Pengajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Clean old sample data
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Kelas::truncate();
        Mapel::truncate();
        Siswa::truncate();
        Pengajaran::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Master Sesi (Jam Belajar)
        $this->call([
            MasterSesiSeeder::class,
            TarifSppSeeder::class,
            TarifGajiSeeder::class,
        ]);

        // 3. Guru-Guru Sampel
        $gurus = [
            ['nama' => 'Ibu Pertiwi', 'email' => 'pertiwi@guru.com', 'no_hp' => '0811111111', 'alamat' => 'Yogyakarta', 'status' => 'senior'],
            ['nama' => 'Pak Bambang', 'email' => 'bambang@guru.com', 'no_hp' => '0822222222', 'alamat' => 'Sleman', 'status' => 'semi'],
            ['nama' => 'Ibu Siti', 'email' => 'siti@guru.com', 'no_hp' => '0833333333', 'alamat' => 'Bantul', 'status' => 'baru'],
        ];

        foreach ($gurus as $g) {
            Guru::updateOrCreate(['email' => $g['email']], $g);
        }

        $this->call([
            CreateUserForExistingGurusSeeder::class,
        ]);

        $guruSenior = Guru::where('status', 'senior')->first();
        $guruSemi = Guru::where('status', 'semi')->first();

        // 4. Data per Jenjang (Mapel -> Kelas -> Siswa)
        $dataJenjang = [
            [
                'jenjang' => 'Pre School',
                'program' => 'Calistung',
                'mapel' => ['Membaca Kebahagiaan', 'Berhitung Ceria'],
                'kelas' => 'TK Bintang Kecil',
                'siswa' => ['Budi Kecil', 'Susi Imut']
            ],
            [
                'jenjang' => 'SD',
                'program' => 'Reguler',
                'mapel' => ['Matematika SD', 'IPA Terpadu', 'Bahasa Indonesia'],
                'kelas' => '6 SD Harapan',
                'siswa' => ['Andi Wijaya', 'Rina Pratama']
            ],
            [
                'jenjang' => 'SMP',
                'program' => 'TKA',
                'mapel' => ['Aljabar SMP', 'Fisika Dasar', 'Bahasa Inggris'],
                'kelas' => '9 SMP Unggulan',
                'siswa' => ['Gibran Rakabuming', 'Kaerul Anam']
            ],
            [
                'jenjang' => 'SMA',
                'program' => 'Reguler',
                'mapel' => ['Kalkulus', 'Kimia Organik', 'Biologi Molekuler'],
                'kelas' => '12 SMA Akselerasi',
                'siswa' => ['Raditya Dika', 'Jessica Mila']
            ],
        ];

        foreach ($dataJenjang as $dj) {
            // Get Jenjang and Program IDs
            $jenjang = \App\Models\Jenjang::firstOrCreate(['nama' => $dj['jenjang']]);
            $program = \App\Models\Program::firstOrCreate(['nama' => $dj['program']]);

            // Create Kelas
            $kelas = Kelas::create([
                'nama_kelas' => $dj['kelas'],
                'keterangan' => 'Kelas sampel for ' . $dj['jenjang'],
            ]);

            // Create Mapels & Link to Class
            foreach ($dj['mapel'] as $mName) {
                $mapel = Mapel::create([
                    'nama' => $mName,
                    'jenjang_id' => $jenjang->id,
                    'program_id' => $program->id,
                ]);

                Pengajaran::create([
                    'kelas_id' => $kelas->id, 
                    'guru_id' => ($dj['jenjang'] == 'SMA' || $dj['jenjang'] == 'SMP') ? $guruSenior->id : $guruSemi->id, // fixed from teacher_id to guru_id
                    'mapel_id' => $mapel->id, 
                ]);
            }

            // Create Siswas
            foreach ($dj['siswa'] as $sName) {
                Siswa::create([
                    'nama' => $sName,
                    'tanggal_lahir' => now()->subYears($dj['jenjang'] == 'SMA' ? 17 : ($dj['jenjang'] == 'SD' ? 10 : 5)),
                    'alamat' => 'Alamat ' . $sName,
                    'no_hp' => '085' . rand(100000, 999999),
                    'kelas_id' => $kelas->id,
                    'tanggal_daftar' => now(),
                    'spp_jatuh_tempo' => now()->addMonth(),
                    'jumlah_mapel' => count($dj['mapel']),
                ]);
            }
        }
    }
}
