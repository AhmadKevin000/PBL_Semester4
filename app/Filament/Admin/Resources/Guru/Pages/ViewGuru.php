<?php

namespace App\Filament\Admin\Resources\Guru\Pages;

use App\Filament\Admin\Resources\Guru\GuruResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGuru extends ViewRecord
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
