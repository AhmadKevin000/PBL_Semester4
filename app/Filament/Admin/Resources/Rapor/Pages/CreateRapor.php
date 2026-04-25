<?php

namespace App\Filament\Admin\Resources\Rapor\Pages;

use App\Filament\Admin\Resources\Rapor\RaporResource;
use App\Filament\Admin\Resources\Rapor\Schemas\RaporForm;
use App\Models\Rapor;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRapor extends CreateRecord
{
    protected static string $resource = RaporResource::class;

    /**
     * Override handleRecordCreation karena kita tidak insert 1 row,
     * melainkan loop per mapel → updateOrCreate.
     * Return "dummy" record yang mewakili siswa+semester combo,
     * atau redirect manual ke list setelah save.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $nilaiMapel = $data['nilai_mapel'] ?? [];

        foreach ($nilaiMapel as $item) {
            Rapor::updateOrCreate(
                [
                    'siswa_id'          => $data['siswa_id'],
                    'ClassTeaching_id' => $item['ClassTeaching_id'],
                    'semester'          => $data['semester'],
                ],
                [
                    'nilai'       => $item['nilai'],
                    'keterangan'  => $item['keterangan'] ?? null,
                ]
            );
        }

        // Kembalikan record pertama yang di-upsert untuk keperluan redirect
        return Rapor::where('siswa_id', $data['siswa_id'])
            ->where('semester', $data['semester'])
            ->first() ?? new Rapor();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
