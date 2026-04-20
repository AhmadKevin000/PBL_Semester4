<?php

namespace App\Console\Commands;

use App\Models\AbsensiGuru;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCheckoutSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-checkout-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis check-out sesi yang melampaui jam selesai + 90 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pemindaian auto-checkout...');

        // Ambil semua absensi yang masih ongoing
        $ongoingAttendances = AbsensiGuru::where('status', 'ongoing')
            ->with(['sesiKelas.jadwalSesi', 'sesiKelas.absensiSiswa'])
            ->get();

        $count = 0;
        $now = Carbon::now();

        foreach ($ongoingAttendances as $absensiGuru) {
            $sessionClass = $absensiGuru->sesiKelas;
            $masterSession = $sessionClass?->jadwalSesi;

            if (!$sessionClass || !$masterSession) {
                continue;
            }

            // Konstruksi waktu selesai: Tanggal Sesi + Jam Selesai Master
            $endTimeString = "{$sessionClass->tanggal->format('Y-m-d')} {$masterSession->jam_selesai}";
            $endDateTime = Carbon::parse($endTimeString);
            
            // Batas waktu: End Time + 90 Menit
            $deadLine = $endDateTime->copy()->addMinutes(90);

            if ($now->greaterThan($deadLine)) {
                $this->info("Memproses auto-checkout untuk: {$sessionClass->kelas?->nama_kelas} (Sesi: {$masterSession->nama})");

                $absensiGuru->update([
                    'check_out' => $endDateTime, // Set ke jam selesai sesuai jadwal
                    'status' => 'selesai',
                ]);
                // Observer otomatis membuat Penggajian dan Cashflow

                // Record di log
                Log::info("Auto-checkout berhasil: AbsensiGuru ID {$absensiGuru->id} untuk {$sessionClass->kelas?->nama_kelas}");
                $count++;
            }
        }

        $this->info("Selesai. Total sesi yang di-auto-checkout: {$count}");
    }
}
