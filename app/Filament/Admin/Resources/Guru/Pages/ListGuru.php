<?php

namespace App\Filament\Admin\Resources\Guru\Pages;

use App\Filament\Admin\Resources\Guru\GuruResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuru extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Guru'),
        ];
    }

}
