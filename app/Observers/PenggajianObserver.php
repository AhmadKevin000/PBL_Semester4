<?php

namespace App\Observers;

use App\Models\Penggajian;

class PenggajianObserver
{
    /**
     * Handle the Penggajian "created" event.
     */
    public function created(Penggajian $penggajian): void
    {
        // Langsung catat ke cashflow jika otomatis paid
        if ($penggajian->status_pembayaran === 'paid') {
            $this->recordCashflow($penggajian);
        }
    }

    /**
     * Handle the Penggajian "updated" event.
     */
    public function updated(Penggajian $penggajian): void
    {
        if ($penggajian->isDirty('status_pembayaran') && $penggajian->status_pembayaran === 'paid') {
            if (!$penggajian->cashflow()->where('type', 'expense')->exists()) {
                $this->recordCashflow($penggajian);
            }
        }
    }

    private function recordCashflow(Penggajian $penggajian)
    {
        $absensiGuru = $penggajian->absensiGuru;
        
        $penggajian->cashflow()->create([
            'type'       => 'expense',
            'kategori'   => 'gaji_guru',
            'nominal'     => $penggajian->nominal,
            'tanggal'    => $penggajian->tanggal_pembayaran ?? now()->toDateString(),
            'sumber_id'  => $penggajian->guru_id,
            'source_snapshot' => $penggajian->guru->nama ?? 'Unknown Guru'
        ]);
    }
}
