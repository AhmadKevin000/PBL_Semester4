<?php

namespace App\Filament\Resources\TarifSpp\Pages;

use App\Filament\Resources\TarifSpp\TarifSppResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTarifSpp extends ListRecords
{
    protected static string $resource = TarifSppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
