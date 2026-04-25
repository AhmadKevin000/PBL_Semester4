<?php

namespace App\Filament\Admin\Resources\AbsensiSiswa\Pages;

use App\Filament\Admin\Resources\AbsensiSiswa\AbsensiSiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbsensiSiswa extends ListRecords
{
    protected static string $resource = AbsensiSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

}
