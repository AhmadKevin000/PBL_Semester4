<?php

namespace App\Console\Commands;

use App\Models\SessionClass;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateDailySessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-sessions {--date= : The specific date to generate for (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis duplikasi jadwal (Sesi Kelas) dari H-7 ke hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDateStr = $this->option('date');
        $targetDate = $targetDateStr ? Carbon::parse($targetDateStr) : Carbon::today();
        $sourceDate = $targetDate->copy()->subDays(7);

        $this->info("Menjalankan Sinkronisasi Sesi. Target: {$targetDate->format('Y-m-d')}, Sumber (H-7): {$sourceDate->format('Y-m-d')}");

        // Fetch all SessionClass records from exactly 7 days ago
        $sourceSessions = SessionClass::whereDate('tanggal', $sourceDate)->get();

        if ($sourceSessions->isEmpty()) {
            $this->warn('Tidak ada data sesi pada H-7. Sinkronisasi dihentikan.');
            return;
        }

        $count = 0;

        foreach ($sourceSessions as $session) {
            // Replicate but update the date, prevent duplicate insertion with updateOrCreate
            $newSession = SessionClass::updateOrCreate([
                'jadwal_sesi_id' => $session->session_id,
                'kelas_id'   => $session->kelas_id,
                'tanggal'    => $targetDate->format('Y-m-d'),
                'semester'   => $session->semester,
            ]);

            if ($newSession->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->info("Proses selesai. {$count} jadwal baru berhasil disalin ke {$targetDate->format('Y-m-d')}.");
        Log::info("GenerateDailySessions: Copied {$count} sessions from {$sourceDate->format('Y-m-d')} to {$targetDate->format('Y-m-d')}.");
    }
}
