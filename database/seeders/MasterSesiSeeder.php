<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama agar tidak duplikat
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\JadwalSesi::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Sesi Reguler (4 slot)
        $regulerSlots = [
            ['start' => '13:00:00', 'end' => '14:30:00'],
            ['start' => '14:30:00', 'end' => '16:00:00'],
            ['start' => '16:00:00', 'end' => '17:30:00'],
            ['start' => '18:00:00', 'end' => '19:30:00'],
        ];

        foreach ($regulerSlots as $index => $slot) {
            \App\Models\JadwalSesi::create([
                'nama' => 'Sesi Reguler ' . ($index + 1),
                'jam_mulai' => $slot['start'],
                'jam_selesai' => $slot['end'],
                'durasi' => 90,
                'max_capacity' => 15,
            ]);
        }
    }
}
