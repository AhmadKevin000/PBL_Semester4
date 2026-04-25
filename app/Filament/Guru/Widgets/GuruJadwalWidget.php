<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use Filament\Widgets\Widget;

class GuruJadwalWidget extends Widget
{
    protected string $view = 'filament.guru.widgets.guru-jadwal-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        $jadwalHariIni = collect();

        if ($guru) {
            $jadwalHariIni = $guru->sesiKelas()
                ->whereDate('tanggal', today())
                ->with([
                    'jadwalSesi',
                    'pengajaran.kelas',
                    'pengajaran.mapel',
                ])
                ->orderBy('tanggal')
                ->get()
                ->map(function ($sesi) {
                    return [
                        'waktu'  => optional($sesi->jadwalSesi)->jam_mulai
                                    ? \Carbon\Carbon::parse($sesi->jadwalSesi->jam_mulai)->format('H:i')
                                    : '-',
                        'selesai' => optional($sesi->jadwalSesi)->jam_selesai
                                    ? \Carbon\Carbon::parse($sesi->jadwalSesi->jam_selesai)->format('H:i')
                                    : '-',
                        'kelas'  => optional($sesi->pengajaran?->kelas)->nama_kelas ?? '-',
                        'mapel'  => optional($sesi->pengajaran?->mapel)->nama ?? '-',
                        'status' => $sesi->absensiGuru ? 'Hadir' : 'Belum Absen',
                    ];
                });
        }

        return [
            'jadwalHariIni' => $jadwalHariIni,
            'tanggal'       => now()->locale('id')->isoFormat('D MMMM Y'),
        ];
    }
}
