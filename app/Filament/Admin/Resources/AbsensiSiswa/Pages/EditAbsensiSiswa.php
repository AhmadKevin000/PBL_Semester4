<?php

namespace App\Filament\Admin\Resources\AbsensiSiswa\Pages;

use App\Filament\Admin\Resources\AbsensiSiswa\AbsensiSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAbsensiSiswa extends EditRecord
{
    protected static string $resource = AbsensiSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
