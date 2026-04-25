<?php

namespace App\Filament\Admin\Resources\Mapel\Pages;

use App\Filament\Admin\Resources\Mapel\MapelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMapel extends ListRecords
{
    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Mata Pelajaran'),
        ];
    }

}
