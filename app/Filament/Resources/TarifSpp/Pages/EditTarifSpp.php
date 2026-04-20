<?php

namespace App\Filament\Resources\TarifSpp\Pages;

use App\Filament\Resources\TarifSpp\TarifSppResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTarifSpp extends EditRecord
{
    protected static string $resource = TarifSppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
