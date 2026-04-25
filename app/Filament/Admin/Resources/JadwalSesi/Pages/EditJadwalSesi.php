<?php

namespace App\Filament\Admin\Resources\JadwalSesi\Pages;

use App\Filament\Admin\Resources\JadwalSesi\JadwalSesiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalSesi extends EditRecord
{
    protected static string $resource = JadwalSesiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
