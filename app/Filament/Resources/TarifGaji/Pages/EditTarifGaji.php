<?php

namespace App\Filament\Resources\TarifGaji\Pages;

use App\Filament\Resources\TarifGaji\TarifGajiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTarifGaji extends EditRecord
{
    protected static string $resource = TarifGajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
