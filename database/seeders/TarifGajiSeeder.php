<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use App\Models\Program;
use App\Models\TarifGaji;
use Illuminate\Database\Seeder;

class TarifGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Jenjangs
        $preSchool = Jenjang::firstOrCreate(['nama' => 'Pre School']);
        $sd = Jenjang::firstOrCreate(['nama' => 'SD']);
        $smp = Jenjang::firstOrCreate(['nama' => 'SMP']);
        $sma = Jenjang::firstOrCreate(['nama' => 'SMA']);

        // Define Programs
        $calistung = Program::firstOrCreate(['nama' => 'Calistung']);
        $reguler = Program::firstOrCreate(['nama' => 'Reguler']);
        $tka = Program::firstOrCreate(['nama' => 'TKA']);

        // Seed Tarif Gaji
        $tarifs = [
            // 1. CALISTUNG (Per Siswa Per Sesi)
            // SalaryService handles multiplication by student count automatically for "Calistung" program.
            [
                'program_id' => $calistung->id,
                'jenjang_id' => $preSchool->id,
                'status_guru' => 'senior',
                'jumlah_siswa_min' => 1,
                'rate' => 7000,
            ],
            [
                'program_id' => $calistung->id,
                'jenjang_id' => $preSchool->id,
                'status_guru' => 'semi',
                'jumlah_siswa_min' => 1,
                'rate' => 6125,
            ],
            [
                'program_id' => $calistung->id,
                'jenjang_id' => $preSchool->id,
                'status_guru' => 'baru',
                'jumlah_siswa_min' => 1,
                'rate' => 5900,
            ],

            // 2. REGULER (Per Sesi Per Kelas berdasarkan jumlah siswa)
            // SD 1-2 anak 15.000, 3-5 anak 20.000
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sd->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 15000,
            ],
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sd->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 3,
                'rate' => 20000,
            ],
            // SMP 1-2 anak 20.000, 3-5 anak 25.000
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $smp->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 20000,
            ],
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $smp->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 3,
                'rate' => 25000,
            ],
            // SMA 1-2 anak 30.000, 3-5 anak 35.000
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sma->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 30000,
            ],
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sma->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 3,
                'rate' => 35000,
            ],

            // 3. TKA (Per Sesi/Kelas berapapun anaknya)
            // SD 30rb, SMP 35rb, SMA 40rb
            [
                'program_id' => $tka->id,
                'jenjang_id' => $sd->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 30000,
            ],
            [
                'program_id' => $tka->id,
                'jenjang_id' => $smp->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 35000,
            ],
            [
                'program_id' => $tka->id,
                'jenjang_id' => $sma->id,
                'status_guru' => 'all',
                'jumlah_siswa_min' => 1,
                'rate' => 40000,
            ],
        ];

        foreach ($tarifs as $tarif) {
            TarifGaji::updateOrCreate(
                [
                    'program_id' => $tarif['program_id'],
                    'jenjang_id' => $tarif['jenjang_id'],
                    'status_guru' => $tarif['status_guru'],
                    'jumlah_siswa_min' => $tarif['jumlah_siswa_min'],
                ],
                $tarif
            );
        }
    }
}
