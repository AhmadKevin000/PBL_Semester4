<?php

namespace App\Filament\Resources\JadwalSesi\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Carbon\Carbon;

class JadwalSesiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Sesi / Label')
                    ->placeholder('Contoh: Sesi Sore 1')
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => static::calculateEnd($set, $get)),

                TextInput::make('durasi')
                    ->label('Durasi (Menit)')
                    ->numeric()
                    ->default(90)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => static::calculateEnd($set, $get)),

                TextInput::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->readOnly()
                    ->dehydrated()
                    ->placeholder('Otomatis'),

            ]);
    }

    protected static function calculateEnd($set, $get)
    {
        $start = $get('jam_mulai');
        $duration = $get('durasi');

        if ($start && $duration) {
            try {
                $startTime = Carbon::parse($start);
                $endTime = $startTime->copy()->addMinutes((int) $duration);
                $set('jam_selesai', $endTime->format('H:i'));
            } catch (\Exception $e) {
                // Ignore errors
            }
        }
    }

}
