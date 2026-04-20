<?php

namespace App\Observers;

use App\Models\Siswa;

class SiswaObserver
{
    /**
     * Handle the Siswa "created" event.
     */
    public function created(Siswa $siswa): void
    {
        
        $financeService = new \App\Services\FinanceService();
        $pendaftaranFee = $financeService->getRegistrationFee();
        
        $kName = $siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
        $actorName = "{$siswa->nama} ({$kName})";

        // Always charge Registration fee on creation
        $siswa->cashflow()->create([
            'type'            => 'income',
            'kategori'        => 'pendaftaran',
            'nominal'          => $pendaftaranFee,
            'tanggal'         => $siswa->tanggal_daftar ?? now()->toDateString(),
            'source_snapshot' => $actorName,
            'sumber_id'        => $siswa->id,
        ]);

        // Charge SPP if class is assigned immediately
        if ($siswa->kelas_id) {
            $this->processInitialSpp($siswa);
        }
    }

    /**
     * Process first month SPP and set jatuh tempo.
     */
    protected function processInitialSpp(Siswa $siswa): void
    {
        // Check if initial SPP already exists for this student to avoid double charging
        $alreadyPaid = $siswa->cashflow()
            ->where('type', 'income')
            ->where('kategori', 'spp')
            ->exists();

        if ($alreadyPaid) {
            return;
        }

        $sppNominal = $siswa->spp_nominal;

        if ($sppNominal > 0) {
            $kName = $siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
            $actorName = "{$siswa->nama} ({$kName})";

            $siswa->cashflow()->create([
                'type'            => 'income',
                'kategori'        => 'spp',
                'nominal'          => $sppNominal,
                'tanggal'         => $siswa->tanggal_daftar ?? now()->toDateString(),
                'source_snapshot' => $actorName,
                'sumber_id'        => $siswa->id,
            ]);

            // Set Jatuh Tempo +30 days from registration
            $siswa->updateQuietly([
                'spp_jatuh_tempo' => \Carbon\Carbon::parse($siswa->tanggal_daftar ?? now())->addDays(30)->toDateString()
            ]);
        }
    }

    /**
     * Handle the Siswa "updated" event.
     */
    public function updated(Siswa $siswa): void
    {
        // If kelas_id was added (changed from null to ID)
        if ($siswa->isDirty('kelas_id')) {
            // If it was just assigned from null, trigger initial SPP payment
            if (is_null($siswa->getOriginal('kelas_id')) && !is_null($siswa->kelas_id)) {
                $this->processInitialSpp($siswa);
            }
        }
    }

    /**
     * Handle the Siswa "deleted" event.
     */
    public function deleted(Siswa $siswa): void
    {
    }

    /**
     * Handle the Siswa "restored" event.
     */
    public function restored(Siswa $siswa): void
    {
    }

    /**
     * Handle the Siswa "force deleted" event.
     */
    public function forceDeleted(Siswa $siswa): void
    {
    }
}
