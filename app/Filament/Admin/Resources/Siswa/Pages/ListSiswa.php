<?php

namespace App\Filament\Admin\Resources\Siswa\Pages;

use App\Filament\Admin\Resources\Siswa\SiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiswa extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Siswa'),
        ];
    }

}
