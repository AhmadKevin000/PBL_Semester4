<?php

namespace App\Filament\Admin\Resources\Mapel\Pages;

use App\Filament\Admin\Resources\Mapel\MapelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMapel extends EditRecord
{
    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
