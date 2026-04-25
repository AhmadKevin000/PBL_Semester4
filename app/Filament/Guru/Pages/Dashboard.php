<?php

namespace App\Filament\Guru\Pages;

use App\Filament\Guru\Widgets\GuruGajiWidget;
use App\Filament\Guru\Widgets\GuruGreetingWidget;
use App\Filament\Guru\Widgets\GuruJadwalWidget;
use App\Filament\Guru\Widgets\GuruStatsWidget;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    // $view adalah non-static di Filament\Pages\Page, harus non-static di sini
    protected string $view = 'filament.guru.pages.dashboard';

    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            GuruGreetingWidget::class,
            GuruStatsWidget::class,
            GuruJadwalWidget::class,
            GuruGajiWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
