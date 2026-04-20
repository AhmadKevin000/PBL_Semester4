<?php

namespace App\Filament\Resources\TarifGaji\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TarifGajiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenjang_id')
                    ->relationship('jenjang', 'nama')
                    ->label('Jenjang')
                    ->placeholder('Semua Jenjang')
                    ->nullable(),
                Select::make('program_id')
                    ->relationship('program', 'nama')
                    ->label('Program')
                    ->required(),
                Select::make('status_guru')
                    ->label('Status Guru')
                    ->options([
                        'all' => 'Semua Status',
                        'baru' => 'Baru',
                        'semi' => 'Semi',
                        'senior' => 'Senior',
                    ])
                    ->default('all')
                    ->required(),
                TextInput::make('jumlah_siswa_min')
                    ->label('Minimal Siswa di Kelas')
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('rate')
                    ->label('Honor/Rate')
                    ->numeric()
                    ->required(),
            ]);
    }

}
