<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use App\Models\Program;
use App\Models\TarifSpp;
use Illuminate\Database\Seeder;

class TarifSppSeeder extends Seeder
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

        // Seed Tarif SPP
        $tarifs = [
            // Calistung Ahe (Pre School) - Mengambil nilai tengah 150rb
            [
                'program_id' => $calistung->id,
                'jenjang_id' => $preSchool->id,
                'nominal'    => 150000,
                'type'       => 'flat',
            ],
            // Reguler SD
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sd->id,
                'nominal'    => 200000,
                'type'       => 'flat',
            ],
            // Reguler SMP
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $smp->id,
                'nominal'    => 250000,
                'type'       => 'flat',
            ],
            // Reguler SMA (100rb per mapel)
            [
                'program_id' => $reguler->id,
                'jenjang_id' => $sma->id,
                'nominal'    => 100000,
                'type'       => 'per_mapel',
            ],
            // TKA SD
            [
                'program_id' => $tka->id,
                'jenjang_id' => $sd->id,
                'nominal'    => 215000,
                'type'       => 'flat',
            ],
            // TKA SMP
            [
                'program_id' => $tka->id,
                'jenjang_id' => $smp->id,
                'nominal'    => 235000,
                'type'       => 'flat',
            ],
        ];

        foreach ($tarifs as $tarif) {
            TarifSpp::updateOrCreate(
                [
                    'program_id' => $tarif['program_id'],
                    'jenjang_id' => $tarif['jenjang_id'],
                ],
                $tarif
            );
        }
    }
}
