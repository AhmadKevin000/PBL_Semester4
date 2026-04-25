<?php

namespace App\Filament\Admin\Resources\Siswa\Pages;

use App\Filament\Admin\Resources\Siswa\SiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
