<?php

namespace App\Filament\Admin\Resources\AbsensiGuru\Pages;

use App\Filament\Admin\Resources\AbsensiGuru\AbsensiGuruResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAbsensiGuru extends EditRecord
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
