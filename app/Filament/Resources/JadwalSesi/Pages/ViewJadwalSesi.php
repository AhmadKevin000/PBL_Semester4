<?php

namespace App\Filament\Resources\JadwalSesi\Pages;

use App\Filament\Resources\JadwalSesi\JadwalSesiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalSesi extends ViewRecord
{
    protected static string $resource = JadwalSesiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
