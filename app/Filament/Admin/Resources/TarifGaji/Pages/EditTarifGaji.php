<?php

namespace App\Filament\Admin\Resources\TarifGaji\Pages;

use App\Filament\Admin\Resources\TarifGaji\TarifGajiResource;
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
