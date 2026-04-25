<?php

namespace App\Filament\Admin\Resources\JadwalSesi\Pages;

use App\Filament\Admin\Resources\JadwalSesi\JadwalSesiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalSesi extends ListRecords
{
    protected static string $resource = JadwalSesiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Master Sesi'),
        ];
    }

}
