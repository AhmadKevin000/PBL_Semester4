<?php

namespace App\Services;

use App\Models\AbsensiGuru;
use App\Models\Guru;

class SalaryService
{
    /**
     * Calculate teacher salary for a given record (AbsensiGuru).
     */
    public function calculate($record, ?int $jumlahSiswaOverride = null): int
    {
       $details = $this->calculateDetailed($record, $jumlahSiswaOverride);
       return (int) $details['total_fee'];
    }

    /**
     * Calculate teacher salary with detailed snapshot data.
     */
    public function calculateDetailed($record, ?int $jumlahSiswaOverride = null): array
    {
        $pengajaran = $record->pengajaran;
        if (!$pengajaran) {
            return $this->emptyResult();
        }

        $guru = $pengajaran->guru;
        $mapel = $pengajaran->mapel;
        $kelas = $pengajaran->kelas;

        if (!$guru || !$mapel || !$kelas) {
            return $this->emptyResult();
        }

        $program = $mapel->program;
        $jenjang = $mapel->jenjang;
        $jumlahSiswa = $jumlahSiswaOverride ?? $record->jumlah_siswa_hadir;
        
        if ($jumlahSiswa <= 0 && $record->status !== 'selesai') {
            $jumlahSiswa = $kelas->siswa()->count();
        }

        // Snapshot support: Use snapshot if record is finished
        $statusSnapshot = ($record instanceof AbsensiGuru && $record->status === 'selesai' && optional($record->penggajian)->rate_snapshot)
            ? optional($record->penggajian)->rate_snapshot > 0 ? $guru->status : $guru->status
            : $guru->status;

        $results = [
            'total_fee' => 0,
            'teacher_status_snapshot' => $statusSnapshot,
            'rate_snapshot' => 0,
            'tarif_gaji_id' => null,
        ];

        if (!$program) return $results;

        // Query the TarifGaji table
        $query = \App\Models\TarifGaji::where('program_id', $program->id)
            ->where(function ($q) use ($statusSnapshot) {
                $q->where('status_guru', $statusSnapshot)
                  ->orWhere('status_guru', 'all');
            })
            ->where('jumlah_siswa_min', '<=', max(1, $jumlahSiswa));
            
        if ($jenjang) {
            $query->where(function ($q) use ($jenjang) {
                $q->where('jenjang_id', $jenjang->id)->orWhereNull('jenjang_id');
            });
        } else {
            $query->whereNull('jenjang_id');
        }

        // Specifically prioritize the highest minimum students matching the class size
        $tarif = $query->orderBy('jumlah_siswa_min', 'desc')
                       ->orderByRaw("CASE WHEN jenjang_id IS NOT NULL THEN 1 ELSE 0 END DESC")
                       ->orderByRaw("CASE WHEN status_guru = 'all' THEN 0 ELSE 1 END DESC")
                       ->first();

        if ($tarif) {
            $rate = (int) $tarif->rate;
            
            // Calistung logic historically multiplies by student count. Other programs are flat class rates.
            if (strtoupper($program->nama) === 'CALISTUNG') {
                $results['total_fee'] = $jumlahSiswa * $rate;
            } else {
                $results['total_fee'] = $rate;
            }
            $results['rate_snapshot'] = $rate;
            $results['tarif_gaji_id'] = $tarif->id;
        }

        return $results;
    }

    private function emptyResult(): array
    {
        return [
            'total_fee' => 0,
            'teacher_status_snapshot' => null,
            'rate_snapshot' => 0,
            'tarif_gaji_id' => null,
        ];
    }
}
