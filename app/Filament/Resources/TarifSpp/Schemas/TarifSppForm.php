<?php

namespace App\Filament\Resources\TarifSpp\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TarifSppForm
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
                TextInput::make('nominal')
                    ->label('Nominal SPP')
                    ->numeric()
                    ->required(),
                Select::make('type')
                    ->label('Tipe Kalkulasi')
                    ->options([
                        'flat' => 'Flat',
                        'per_subject' => 'Per Mata Pelajaran',
                    ])
                    ->default('flat')
                    ->required(),
            ]);
    }

}
