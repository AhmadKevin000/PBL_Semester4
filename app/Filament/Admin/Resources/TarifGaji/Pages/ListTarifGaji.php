<?php

namespace App\Filament\Admin\Resources\TarifGaji\Pages;

use App\Filament\Admin\Resources\TarifGaji\TarifGajiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTarifGaji extends ListRecords
{
    protected static string $resource = TarifGajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
