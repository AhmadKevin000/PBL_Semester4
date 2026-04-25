<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Guru', \App\Models\Guru::count())
                ->description('Tenaga pendidik aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Total Siswa', \App\Models\Siswa::count())
                ->description('Siswa terdaftar saat ini')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
            Stat::make('Total Kelas', \App\Models\Kelas::count())
                ->description('Ruang kelas tersedia')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('warning'),
        ];
    }

}
