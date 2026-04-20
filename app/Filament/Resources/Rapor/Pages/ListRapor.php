<?php

namespace App\Filament\Resources\Rapor\Pages;

use App\Filament\Resources\Rapor\RaporResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRapor extends ListRecords
{
    protected static string $resource = RaporResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

}
