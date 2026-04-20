<?php

namespace App\Services;

use App\Models\AbsensiGuru;
use App\Models\Cashflow;
use App\Models\SesiKelas;

class CashflowService
{
    public function recordGajiGuru($record, float $amount): Cashflow
    {
        $tanggal = now();

        if ($record instanceof SesiKelas) {
            $tanggal = $record->tanggal;
        } elseif ($record instanceof AbsensiGuru) {
            $tanggal = $record->sesiKelas?->tanggal ?? now();
        }

        $guru = $record instanceof AbsensiGuru ? $record->guru : ($record->pengajaran->guru ?? null);
        $kName = $record instanceof AbsensiGuru ? ($record->kelas->nama_kelas ?? '?') : ($record->pengajaran->kelas->nama_kelas ?? '?');
        $mName = $record instanceof AbsensiGuru ? ($record->mapel?->nama ?? '?') : ($record->pengajaran->mapel->nama ?? '?');
        
        $actorName = $guru ? "{$guru->nama} ({$kName} - {$mName})" : "Unknown Guru ({$kName} - {$mName})";
        $ownerId = $guru->id ?? null;

        return Cashflow::create([
            'type' => 'expense',
            'kategori' => 'gaji_guru',
            'nominal' => $amount,
            'tanggal' => $tanggal,
            'source_snapshot' => $actorName,
            'sumber_id' => $ownerId,
            'reference_id' => $record->id,
            'reference_type' => get_class($record),
        ]);
    }
}
