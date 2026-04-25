<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use Filament\Widgets\Widget;

class GuruGreetingWidget extends Widget
{
    protected string $view = 'filament.guru.widgets.guru-greeting-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        $namaGuru = $guru?->nama ?? $user->name;
        $statusGuru = $guru?->status ?? '-';

        $jam = (int) now()->format('H');
        $greeting = match (true) {
            $jam < 11  => 'Selamat Pagi',
            $jam < 15  => 'Selamat Siang',
            $jam < 18  => 'Selamat Sore',
            default    => 'Selamat Malam',
        };

        return [
            'namaGuru'   => $namaGuru,
            'statusGuru' => $statusGuru,
            'greeting'   => $greeting,
            'tanggal'    => now()->locale('id')->isoFormat('dddd, D MMMM Y'),
        ];
    }
}
