<?php

namespace App\Filament\Resources\Mapel\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MapelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Select::make('jenjang_id')
                    ->relationship('jenjang', 'nama')
                    ->required(),
                Select::make('program_id')
                    ->relationship('program', 'nama')
                    ->required(),
            ]);
    }

}
