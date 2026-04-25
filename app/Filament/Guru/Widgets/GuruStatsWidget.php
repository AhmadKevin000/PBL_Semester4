<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuruStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)
            ->with(['pengajaran.kelas', 'sesiKelas'])
            ->first();

        if (! $guru) {
            return [
                Stat::make('Kelas Diajar', 0)->description('Belum ada kelas'),
                Stat::make('Sesi Hari Ini', 0)->description('Tidak ada sesi'),
                Stat::make('Total Siswa', 0)->description('Belum ada siswa'),
            ];
        }

        // Jumlah kelas unik yang diajar
        $jumlahKelas = $guru->pengajaran()->distinct('kelas_id')->count('kelas_id');

        // Sesi hari ini
        $sesiHariIni = $guru->sesiKelas()
            ->whereDate('tanggal', today())
            ->count();

        // Total siswa dari semua kelas yang diajar
        $kelasIds = $guru->pengajaran()->pluck('kelas_id')->unique()->toArray();
        $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();

        return [
            Stat::make('Kelas Diajar', $jumlahKelas)
                ->description('Total kelas yang Anda ampu')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary'),

            Stat::make('Sesi Hari Ini', $sesiHariIni)
                ->description('Jadwal mengajar hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($sesiHariIni > 0 ? 'success' : 'gray'),

            Stat::make('Total Siswa', $totalSiswa)
                ->description('Siswa di kelas Anda')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
        ];
    }
}
