<?php

namespace App\Filament\Admin\Resources\AbsensiGuru\Pages;

use App\Filament\Admin\Resources\AbsensiGuru\AbsensiGuruResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsensiGuru extends CreateRecord
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()->label('Check In');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tanggal'] = today()->toDateString();
        $data['check_in'] = now();
        $data['check_out'] = now()->addMinutes(90);
        $data['total_sesi'] = 1;

        return $data;
    }

}
